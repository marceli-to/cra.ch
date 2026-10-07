<?php

namespace Tests\Feature\Api;

use App\Models\Grid;
use App\Models\GridItem;
use App\Models\Image;
use App\Models\Project;

/**
 * Image records (upload and store: ImageUploadTest, ImageStoreTest)
 */
class ImageApiTest extends ApiTestCase
{
    public function testUpdateCaption()
    {
        $image = $this->image();

        $this->putJson("/api/image/{$image->id}", ['id' => $image->id, 'caption' => 'Neu', 'description' => 'D'])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertSame('Neu', $image->fresh()->caption);
        $this->assertSame('D', $image->fresh()->description);
    }

    public function testTogglePublishAndPreview()
    {
        $image = $this->image(['publish' => 0, 'preview' => 0]);

        $this->getJson("/api/image/state/{$image->id}")->assertOk()->assertContent('1');
        $this->assertEquals(1, $image->fresh()->publish);
        $this->getJson("/api/image/state/{$image->id}")->assertOk()->assertContent('0');

        $this->getJson("/api/image/preview/state/{$image->id}")->assertOk()->assertContent('1');
        $this->assertEquals(1, $image->fresh()->preview);
    }

    public function testCoords()
    {
        $image = $this->image();

        $this->putJson("/api/image/coords/{$image->id}", ['coords_w' => 100.123456789012345, 'coords_h' => 50, 'coords_x' => 1.5, 'coords_y' => 2])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $image = $image->fresh();
        $this->assertEqualsWithDelta(100.123456789012, $image->coords_w, 1e-9);
        $this->assertEquals(50, $image->coords_h);
        $this->assertEquals(1.5, $image->coords_x);
        $this->assertSame('100,50,1,2', $image->coords);
    }

    public function testOrder()
    {
        $a = $this->image();
        $b = $this->image();

        $this->postJson('/api/images/order', ['images' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertEquals(1, $a->fresh()->order);
        $this->assertEquals(0, $b->fresh()->order);
    }

    public function testDestroyByName()
    {
        $name = uniqid() . '_destroy-test.jpg';
        $path = storage_path('app/public/uploads/' . $name);
        file_put_contents($path, 'x');
        $image = $this->image(['name' => $name]);
        $grid = Grid::create(['layout' => '1', 'gridable_type' => Project::class, 'gridable_id' => 1]);
        $item = GridItem::create(['grid_id' => $grid->id, 'position' => 0, 'image_id' => $image->id]);

        try {
            $this->deleteJson("/api/image/{$name}")->assertOk()->assertExactJson(['successfully deleted']);

            $this->assertSoftDeleted('images', ['id' => $image->id]);
            // The slot stays, empty
            $this->assertNull($item->fresh()->image_id);
            $this->assertFileDoesNotExist($path);
        } finally {
            @unlink($path);
        }
    }

    public function testDestroyRejectsPaths()
    {
        $this->deleteJson('/api/image/..')->assertNotFound();
        $this->deleteJson('/api/image/.gitignore')->assertNotFound();
    }

    public function testListAndFind()
    {
        $image = $this->image(['caption' => 'C']);

        $this->getJson('/api/images')->assertOk()->assertJsonPath('data.0.caption', 'C')->assertJsonPath('data.0.coords', '0,0,0,0');
        $this->getJson("/api/image/{$image->id}")->assertOk()->assertJsonPath('id', $image->id)->assertJsonPath('caption', 'C');
    }
}
