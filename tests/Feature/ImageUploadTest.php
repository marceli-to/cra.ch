<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    private array $stored = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(new User(), 'sanctum');
    }

    protected function tearDown(): void
    {
        foreach ($this->stored as $name) {
            @unlink(storage_path('app/public/uploads/' . $name));
        }
        parent::tearDown();
    }

    private function image(int $width, int $height, string $format, string $name): UploadedFile
    {
        $image = new \Imagick();
        $image->newImage($width, $height, new \ImagickPixel('#808080'));
        $image->setImageFormat($format);
        $path = tempnam(sys_get_temp_dir(), 'upload');
        $image->writeImage($path);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function upload(UploadedFile $file)
    {
        $response = $this->postJson('/api/image/upload', ['file' => $file]);
        if ($response->status() === 200) {
            $this->stored[] = $response->json('name');
        }
        return $response;
    }

    public function testAcceptsJpgAndPng()
    {
        $this->upload($this->image(1200, 800, 'jpeg', 'photo.jpg'))
            ->assertOk()
            ->assertJson(['extension' => 'jpg', 'orientation' => 'landscape', 'ratio' => '1200x800']);

        $this->upload($this->image(800, 1200, 'png', 'plan.png'))
            ->assertOk()
            ->assertJson(['extension' => 'png', 'orientation' => 'portrait']);
    }

    public function testScalesDownOversizedImages()
    {
        config(['images.max_edge' => 3000]);

        $response = $this->upload($this->image(6000, 2000, 'jpeg', 'wide.jpg'))
            ->assertOk()
            ->assertJson(['ratio' => '3000x1000']);

        [$width, $height] = getimagesize(storage_path('app/public/uploads/' . $response->json('name')));
        $this->assertSame([3000, 1000], [$width, $height]);
    }

    public function testRejectsOtherTypes()
    {
        $this->upload($this->image(400, 400, 'gif', 'anim.gif'))
            ->assertStatus(422)
            ->assertJson(['error' => 'Erlaubt sind nur JPG- und PNG-Dateien.']);

        $this->upload(UploadedFile::fake()->create('plan.pdf', 100, 'application/pdf'))
            ->assertStatus(422);
    }

    public function testRejectsContentThatDoesNotMatchTheName()
    {
        // A real file: fake() files report the mime type of their name
        $path = tempnam(sys_get_temp_dir(), 'upload');
        file_put_contents($path, '<?php echo "hi";');

        $this->upload(new UploadedFile($path, 'photo.jpg', null, null, true))
            ->assertStatus(422)
            ->assertJson(['error' => 'Der Inhalt der Datei ist kein JPG oder PNG.']);

        $this->upload($this->image(400, 400, 'gif', 'renamed.jpg'))
            ->assertStatus(422);
    }

    public function testRejectsTooManyPixels()
    {
        config(['images.upload.max_megapixels' => 10]);

        $this->upload($this->image(4000, 3000, 'jpeg', 'huge.jpg'))
            ->assertStatus(422)
            ->assertJsonPath('error', 'Das Bild hat 4000×3000 Pixel (12 MP), erlaubt sind max. 10 MP.');
    }

    public function testRejectsTooLargeFiles()
    {
        config(['images.upload.max_kb' => 100]);

        $this->upload(UploadedFile::fake()->image('big.jpg', 100, 100)->size(200))
            ->assertStatus(422)
            ->assertJson(['error' => 'Die Datei ist grösser als 0 MB.']);
    }

    public function testRequiresLogin()
    {
        $this->app['auth']->forgetGuards();

        $this->postJson('/api/image/upload', ['file' => $this->image(100, 100, 'jpeg', 'a.jpg')])
            ->assertUnauthorized();
    }
}
