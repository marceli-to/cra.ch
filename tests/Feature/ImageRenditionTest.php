<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Support\Glide;
use App\Support\ImageSupport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ImageRenditionTest extends TestCase
{
    use RefreshDatabase;

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

    private function record(string $name, array $crop = [0, 0, 0, 0]): Image
    {
        [$w, $h, $x, $y] = $crop;

        return Image::create([
            'uuid' => (string) \Str::uuid(), 'name' => $name, 'original_name' => $name, 'extension' => 'jpg', 'size' => 1,
            'coords_w' => $w, 'coords_h' => $h, 'coords_x' => $x, 'coords_y' => $y,
        ]);
    }

    private function dimensions($response): array
    {
        return array_slice(getimagesizefromstring($response->getContent()), 0, 2);
    }

    public function testSignedUrlScalesTheLongerSideAndNeverUpscales()
    {
        $landscape = $this->upload(3000, 2000);
        $portrait = $this->upload(2000, 3000);

        $this->assertSame([1600, 1067], $this->dimensions($this->get(Glide::url($landscape, 1600))));
        $this->assertSame([1067, 1600], $this->dimensions($this->get(Glide::url($portrait, 1600))));
        $this->assertSame([2000, 3000], $this->dimensions($this->get(Glide::url($portrait, 3200))));
    }

    public function testSignedUrlIsCachedForAYear()
    {
        $name = $this->upload(800, 600);

        $this->get(Glide::url($name, 600))->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg')
            ->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');
    }

    public function testUnsignedOrTamperedUrlsAre404()
    {
        $name = $this->upload(800, 600);
        $url = Glide::url($name, 600);

        $this->get("/img/{$name}?w=600&h=600&fit=max")->assertNotFound();
        $this->get(str_replace('w=600', 'w=601', $url))->assertNotFound();
        $this->get(str_replace($name, $this->upload(800, 600), $url))->assertNotFound();
    }

    public function testModernFormats()
    {
        $name = $this->upload(800, 600);

        $this->assertNotEmpty(ImageSupport::modernFormats(), 'Imagick here writes WebP at least');

        foreach (ImageSupport::modernFormats() as $format) {
            $response = $this->get(Glide::url($name, 600, null, $format))->assertOk()->assertHeader('Content-Type', "image/{$format}");
            $this->assertSame([600, 450], $this->dimensions($response));
        }
    }

    public function testPngStaysPng()
    {
        $name = $this->upload(800, 600, 'png');

        $this->get(Glide::url($name, 600))->assertOk()->assertHeader('Content-Type', 'image/png');
    }

    public function testModelCropAndSize()
    {
        $image = $this->record($this->upload(3000, 2000), [1000, 1500, 1000, 250]);

        $this->assertSame([3000, 2000], [$image->width, $image->height]);
        $this->assertSame([1000, 1500, 1000, 250], $image->crop());
        // 1000 × 1500 portrait, scaled to 1200 high
        $this->assertSame([800, 1200], $this->dimensions($this->get($image->url(1200))));
    }

    public function testCropIsCutAtTheImageEdges()
    {
        // Stored 3000 × 2000 with EXIF "rotate 90°": displayed 2000 × 3000.
        // A landscape crop of the stored size reaches past the right edge.
        $image = $this->record($this->upload(3000, 2000, 'jpg', 6), [3000, 2000, 0, 0]);

        $this->assertSame([2000, 3000], [$image->width, $image->height]);
        $this->assertSame([2000, 2000, 0, 0], $image->crop());
        $this->assertSame([1600, 1600], $this->dimensions($this->get($image->url(1600))));
    }

    public function testLegacyCropRedirectsToTheSignedUrl()
    {
        $image = $this->record($this->upload(3000, 2000), [1000, 1500, 1000, 250]);

        // The coords in the URL don't matter: the record's crop is used
        $this->get("/img/crop/{$image->name}/1600/1,1,0,0")
            ->assertStatus(301)
            ->assertRedirect($image->url(1600))
            ->assertHeader('Cache-Control', 'max-age=3600, public');

        $this->get("/img/crop/{$image->name}")->assertRedirect($image->url(2400));
        $this->get("/img/crop/{$image->name}/1500/0,0,0,0/3x2?fm=webp")->assertRedirect($image->url(1500, 'webp'));
    }

    public function testLegacyCropOnlyKnowsTheSizesInUse()
    {
        $image = $this->record($this->upload(800, 600));

        $this->get("/img/crop/{$image->name}/1601")->assertNotFound();
        $this->get("/img/crop/{$image->name}/abc")->assertNotFound();
        $this->get('/img/crop/missing.jpg/1600')->assertNotFound();
    }

    public function testThumbnailAndOriginal()
    {
        $name = $this->upload(800, 600);

        $this->assertSame([300, 300], $this->dimensions($this->get("/img/thumbnail/{$name}")));
        $this->get("/img/original/{$name}")->assertOk()->assertHeader('Content-Type', 'image/jpeg');
        $this->get('/img/original/.gitignore')->assertNotFound();
        $this->get('/img/original/..%2F..%2F.env')->assertNotFound();
        $this->get("/img/large/{$name}")->assertNotFound();
    }

    public function testComponentOffersModernFormatsPerBreakpoint()
    {
        $image = $this->record($this->upload(3000, 2000));

        $html = Blade::render('<x-image :image="$image" :maxSizes="[1024 => 900, 0 => 1200]" width="1600" height="1000" />', ['image' => $image]);

        $expected = [];
        foreach (ImageSupport::modernFormats() as $format) {
            $expected[] = 'media="(min-width: 1024px)"  type="image/' . $format . '" data-srcset="' . e($image->url(900, $format)) . '"';
        }
        $expected[] = '<source media="(min-width: 1024px)" data-srcset="' . e($image->url(900)) . '">';
        foreach (ImageSupport::modernFormats() as $format) {
            $expected[] = 'type="image/' . $format . '" data-srcset="' . e($image->url(1200, $format)) . '"';
        }
        $expected[] = 'data-src="' . e($image->url(1200)) . '"';

        $position = 0;
        foreach ($expected as $fragment) {
            $found = strpos($html, $fragment, $position);
            $this->assertNotFalse($found, "Missing or out of order: {$fragment}\n\n{$html}");
            $position = $found;
        }
    }
}
