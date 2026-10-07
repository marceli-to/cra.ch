<?php
namespace App\Actions\Image;
use App\Services\ImageResizer;
use Illuminate\Http\UploadedFile;

/**
 * Store an uploaded image in storage/app/public/uploads as
 * `{uniqid}_{sanitized original name}`, scaled down to images.max_edge
 */
class UploadAction
{
  /**
   * @return array{name: string, original_name: string, extension: string, size: int, orientation: string, ratio: string}
   */
  public function execute(UploadedFile $file): array
  {
    $name = $this->sanitize($file->getClientOriginalName());
    $filename = uniqid() . '_' . $name;
    $directory = storage_path('app/public/uploads');
    $file->move($directory, $filename);
    $path = $directory . '/' . $filename;

    $resizer = app(ImageResizer::class);
    $resizer->resize($path, config('images.max_edge'));
    [$width, $height] = $resizer->dimensions($path);

    return [
      'name' => $filename,
      'original_name' => $name,
      'extension' => pathinfo($path, PATHINFO_EXTENSION),
      'size' => filesize($path),
      'orientation' => $width > $height ? 'landscape' : 'portrait',
      'ratio' => $width . 'x' . $height,
    ];
  }

  /**
   * Letters, digits, `.`, `_` and `-`; whitespace becomes `-` (as the
   * old Media service did)
   */
  private function sanitize(string $name): string
  {
    $strip = ['~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '=', '+', '[', '{', ']', '}', '\\', '|', ';', ':', '"', "'", '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8211;', '&#8212;', 'â€—', 'â€“', ',', '<', '>', '/', '?'];
    $name = trim(str_replace($strip, '', strip_tags(trim($name))));
    $name = preg_replace('/\s+/', '-', $name);

    return preg_replace('/[^a-zA-Z0-9._\-]/', '', $name);
  }
}
