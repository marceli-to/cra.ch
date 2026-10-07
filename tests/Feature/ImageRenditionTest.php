<?php

namespace Tests\Feature;

use App\Support\Glide;
use Tests\TestCase;

class ImageRenditionTest extends TestCase
{
    private array $files = [];

    protected function tearDown(): void
    {
        foreach ($this->files as $name) {
            Glide::forget($name);
            @unlink(storage_path('app/public/uploads/' . $name));
        }
        parent::tearDown();
    }

    private function upload(int $width, int $height, string $format = 'jpg', int $orientation = 1): string
    {
        $name = 'test_rendition_' . uniqid() . '.' . $format;
        $image = new \Imagick();
        $image->newImage($width, $height, new \ImagickPixel('#808080'));
        $image->setImageFormat($format === 'jpg' ? 'jpeg' : $format);
        $path = storage_path('app/public/uploads/' . $name);
        $image->writeImage($path);

        if ($orientation !== 1) {
            // Imagick doesn't write an EXIF block: insert a minimal one
            // (big-endian TIFF, one IFD entry: Orientation) after SOI
            $tiff = "MM\x00\x2A\x00\x00\x00\x08" . "\x00\x01" . "\x01\x12\x00\x03\x00\x00\x00\x01" . pack('n', $orientation) . "\x00\x00" . "\x00\x00\x00\x00";
            $app1 = "Exif\x00\x00" . $tiff;
            $jpeg = file_get_contents($path);
            file_put_contents($path, substr($jpeg, 0, 2) . "\xFF\xE1" . pack('n', strlen($app1) + 2) . $app1 . substr($jpeg, 2));
        }
        $this->files[] = $name;

        return $name;
    }

    private function dimensions($response): array
    {
        return array_slice(getimagesizefromstring($response->getContent()), 0, 2);
    }

    public function testOriginal()
    {
        $name = $this->upload(800, 600);

        $this->get("/img/original/{$name}")->assertOk()->assertHeader('Content-Type', 'image/jpeg');
    }

    public function testThumbnailIsASquare()
    {
        $name = $this->upload(800, 600);

        $response = $this->get("/img/thumbnail/{$name}")->assertOk();
        $this->assertSame([300, 300], $this->dimensions($response));
    }

    public function testCropScalesTheLongerSideAndNeverUpscales()
    {
        $landscape = $this->upload(3000, 2000);
        $portrait = $this->upload(2000, 3000);

        $this->assertSame([1600, 1067], $this->dimensions($this->get("/img/crop/{$landscape}/1600/0,0,0,0")));
        $this->assertSame([1067, 1600], $this->dimensions($this->get("/img/crop/{$portrait}/1600/0,0,0,0")));
        $this->assertSame([2400, 1600], $this->dimensions($this->get("/img/crop/{$landscape}")));
        $this->assertSame([1733, 2600], $this->dimensions($this->get("/img/crop/{$portrait}/2600")));
    }

    public function testCropUsesTheCoords()
    {
        $name = $this->upload(3000, 2000);

        // 1000 × 1500 portrait from the middle, scaled to 1200 high
        $this->assertSame([800, 1200], $this->dimensions($this->get("/img/crop/{$name}/1200/1000,1500,1000,250")));

        // The admin sends the stored decimals
        $this->assertSame([800, 1200], $this->dimensions($this->get("/img/crop/{$name}/1200/1000.000000000000,1500.000000000000,1000.000000000000,250.000000000000")));

        // Clamped to the image
        $this->assertSame([500, 500], $this->dimensions($this->get("/img/crop/{$name}/1200/1000,1000,2500,1500")));
    }

    public function testCropToRatioWithoutCoords()
    {
        $name = $this->upload(3000, 3000);

        $this->assertSame([1200, 800], $this->dimensions($this->get("/img/crop/{$name}/1200/0,0,0,0/3x2")));
    }

    public function testCoordsAreInTheRotatedImage()
    {
        // Stored 3000 × 2000 with EXIF "rotate 90°": displayed 2000 × 3000
        $name = $this->upload(3000, 2000, 'jpg', \Imagick::ORIENTATION_RIGHTTOP);

        $this->assertSame([1067, 1600], $this->dimensions($this->get("/img/crop/{$name}/1600/0,0,0,0")));
        $this->assertSame([1000, 1000], $this->dimensions($this->get("/img/crop/{$name}/1600/1000,1000,1000,2000")));
    }

    public function testPngStaysPng()
    {
        $name = $this->upload(800, 600, 'png');

        $this->get("/img/crop/{$name}/600/0,0,0,0")->assertOk()->assertHeader('Content-Type', 'image/png');
    }

    public function testRejectsWhatImageCacheRejected()
    {
        $name = $this->upload(800, 600);

        $this->get("/img/crop/{$name}/2601/0,0,0,0")->assertNotFound();
        $this->get("/img/crop/{$name}/0/0,0,0,0")->assertNotFound();
        $this->get("/img/crop/{$name}/abc")->assertNotFound();
        $this->get("/img/crop/{$name}/1600/1,2,3")->assertNotFound();
        $this->get("/img/crop/{$name}/1600/0,0,0,0/wide")->assertNotFound();
        $this->get('/img/crop/missing.jpg/1600')->assertNotFound();
        $this->get('/img/original/.gitignore')->assertNotFound();
        $this->get('/img/original/..%2F..%2F.env')->assertNotFound();
        $this->get("/img/large/{$name}")->assertNotFound();
    }
}
