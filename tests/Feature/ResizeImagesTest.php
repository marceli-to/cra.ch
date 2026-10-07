<?php

namespace Tests\Feature;

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResizeImagesTest extends TestCase
{
    use RefreshDatabase;

    private string $name = 'test_resize_command.jpg';
    private string $backups;

    protected function setUp(): void
    {
        parent::setUp();

        $this->backups = sys_get_temp_dir() . '/resize-images-test-' . uniqid();
        config(['images.backup_path' => $this->backups]);

        $image = new \Imagick();
        $image->newImage(8000, 4000, new \ImagickPixel('#808080'));
        $image->setImageFormat('jpeg');
        $image->writeImage($this->path());
    }

    protected function tearDown(): void
    {
        @unlink($this->path());
        @unlink("{$this->backups}/{$this->name}");
        @rmdir($this->backups);
        parent::tearDown();
    }

    private function path(): string
    {
        return storage_path('app/public/uploads/' . $this->name);
    }

    private function record(array $attributes = []): Image
    {
        return Image::create(array_merge([
            'uuid' => (string) \Str::uuid(),
            'name' => $this->name,
            'original_name' => $this->name,
            'extension' => 'jpg',
            'size' => 1,
            'ratio' => '8000x4000',
            'coords_w' => 4000,
            'coords_h' => 2000,
            'coords_x' => 2000,
            'coords_y' => 1000,
        ], $attributes));
    }

    public function testDryRunChangesNothing()
    {
        $this->record();

        $this->artisan('images:resize', ['--max' => 4000, '--file' => [$this->name], '--dry-run' => true])
            ->assertSuccessful();

        $this->assertSame([8000, 4000], array_slice(getimagesize($this->path()), 0, 2));
        $this->assertFileDoesNotExist("{$this->backups}/{$this->name}");
    }

    public function testResizesAndScalesCrops()
    {
        $image = $this->record();
        $deleted = $this->record(['coords_w' => 0, 'coords_h' => 0, 'coords_x' => 0, 'coords_y' => 0]);
        $deleted->delete();

        $this->artisan('images:resize', ['--max' => 4000, '--file' => [$this->name]])
            ->assertSuccessful();

        $this->assertSame([4000, 2000], array_slice(getimagesize($this->path()), 0, 2));
        $this->assertSame([8000, 4000], array_slice(getimagesize("{$this->backups}/{$this->name}"), 0, 2));

        $image->refresh();
        $this->assertEquals([2000, 1000, 1000, 500], [$image->coords_w, $image->coords_h, $image->coords_x, $image->coords_y]);
        $this->assertSame('4000x2000', $image->ratio);
        $this->assertSame('2000,1000,1000,500', $image->coords);

        // Uncropped records stay uncropped
        $deleted = Image::withTrashed()->find($deleted->id);
        $this->assertEquals(0, $deleted->coords_w);
        $this->assertSame('4000x2000', $deleted->ratio);
    }

    public function testSecondRunKeepsTheFirstBackup()
    {
        $this->record();

        $this->artisan('images:resize', ['--max' => 4000, '--file' => [$this->name]])->assertSuccessful();
        $this->artisan('images:resize', ['--max' => 4000, '--file' => [$this->name]])
            ->expectsOutputToContain('0 of the originals')
            ->assertSuccessful();
        $this->artisan('images:resize', ['--max' => 2000, '--file' => [$this->name]])->assertSuccessful();

        $this->assertSame([2000, 1000], array_slice(getimagesize($this->path()), 0, 2));
        $this->assertSame([8000, 4000], array_slice(getimagesize("{$this->backups}/{$this->name}"), 0, 2));
        $this->assertEquals(1000, Image::first()->coords_w);
    }
}
