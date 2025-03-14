<?php
namespace App\Http\Controllers;
use App\Facades\ImageCache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class ImageController extends Controller
{
  /**
   * Get HTTP response of image with template applied.
   *
   * @param  string $template
   * @param  string $filename
   * @param  int|null $maxSize
   * @param  string|null $coords
   * @return Illuminate\Http\Response
   */
  public function getResponse($template, $filename, $maxSize = null, $coords = null)
  {
    $path = ImageCache::getCachedImage($template, $filename, $maxSize, $coords);
    
    if (!$path || !file_exists($path)) {
      abort(404);
    }
    
    $mime = mime_content_type($path);
    $content = file_get_contents($path);
    
    return response($content)
      ->header('Content-Type', $mime)
      ->header('Cache-Control', 'public, max-age=31536000');
  }
}
