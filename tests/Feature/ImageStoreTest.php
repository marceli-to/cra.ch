<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageStoreTest extends TestCase
{
    use RefreshDatabase;

    private string $name = 'test_image_store.jpg';

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(new User(), 'sanctum');
        file_put_contents(storage_path('app/public/uploads/' . $this->name), 'x');
    }

    protected function tearDown(): void
    {
        @unlink(storage_path('app/public/uploads/' . $this->name));
        parent::tearDown();
    }

    private function store(array $data = [])
    {
        return $this->postJson('/api/image', array_merge([
            'name' => $this->name,
            'original_name' => $this->name,
            'extension' => 'jpg',
            'size' => 1,
            'imageable_id' => null,
            'imageable_type' => null,
        ], $data));
    }

    public function testStoresAnUnattachedImage()
    {
        $id = $this->store()->assertOk()->json('imageId');

        $image = Image::find($id);
        $this->assertNull($image->imageable_type);
        $this->assertNull($image->imageable_id);
    }

    public function testStoresAnImageOfAnAllowedModel()
    {
        $id = $this->store(['imageable_type' => 'Project', 'imageable_id' => 5])->assertOk()->json('imageId');

        $image = Image::find($id);
        $this->assertSame('App\Models\Project', $image->imageable_type);
        $this->assertEquals(5, $image->imageable_id);
    }

    public function testRejectsOtherTypes()
    {
        foreach (['User', 'Image', 'GridItem', '..\\Providers\\AppServiceProvider', 'App\\Models\\Project', 'project'] as $type) {
            $this->store(['imageable_type' => $type, 'imageable_id' => 1])
                ->assertStatus(422)
                ->assertJsonValidationErrors('imageable_type');
        }

        $this->store(['imageable_type' => 'Project'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('imageable_id');

        $this->assertSame(0, Image::count());
    }

    public function testRejectsFilesThatWereNotUploaded()
    {
        foreach (['missing.jpg', '../uploads/' . $this->name, '..', '.gitignore', ''] as $name) {
            $this->store(['name' => $name])
                ->assertStatus(422)
                ->assertJsonValidationErrors('name');
        }

        $this->assertSame(0, Image::count());
    }
}
