<?php
namespace App\Http\Controllers;
use App\Actions\Image\RenderAction;
use App\Models\Image;
use App\Support\Glide;
use App\Support\ImageSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Glide\Signatures\SignatureException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serves uploads through Glide:
 *
 *   /img/{file}?...&s=...                       signed, built by Image::url()
 *   /img/original/{file}                        admin
 *   /img/thumbnail/{file}                       admin, 300 × 300
 *   /img/small/{file}                           admin, whole image within 400 × 400
 *   /img/crop/{file}/{maxSize?}/{coords?}/...   image-cache's URL, 301 to the signed one
 */
class ImageController extends Controller
{
  /**
   * Sizes the old /img/crop URLs used: the site's, the admin's preview
   * (1500) and image-cache's default when the URL has none (2400)
   */
  public const LEGACY_SIZES = [900, 1000, 1200, 1500, 1600, 2000, 2400, 2600];

  public const LEGACY_DEFAULT_SIZE = 2400;

  /**
   * Renders exactly what the signed parameters ask for
   */
  public function show(Request $request, string $filename): Response
  {
    $this->source($filename);

    try
    {
      Glide::signature()->validateRequest('img/' . $filename, $request->query());
    }
    catch (SignatureException)
    {
      abort(404);
    }

    // The crop is part of the URL, so a re-crop gets a new one
    return $this->respond($filename, $request->except('s'), 31536000, ['immutable']);
  }

  public function original(string $filename): BinaryFileResponse
  {
    return response()->file($this->source($filename), $this->cacheHeaders(3600));
  }

  /**
   * 300 × 300, cropped to fill
   */
  public function thumbnail(string $filename): Response
  {
    $this->source($filename);

    return $this->respond($filename, ['w' => 300, 'h' => 300, 'fit' => 'crop'], 3600);
  }

  /**
   * The whole image within 400 × 400 (the grid's image picker)
   */
  public function small(string $filename): Response
  {
    $this->source($filename);

    return $this->respond($filename, ['w' => 400, 'h' => 400, 'fit' => 'max'], 3600);
  }

  /**
   * The crop comes from the record, not the URL, so this can't render
   * arbitrary crops; only the sizes that were in use
   */
  public function legacyCrop(Request $request, string $filename, ?string $maxSize = null): RedirectResponse
  {
    $this->source($filename);

    $size = $maxSize === null ? self::LEGACY_DEFAULT_SIZE : (int) $maxSize;
    abort_unless(($maxSize === null || ctype_digit($maxSize)) && in_array($size, self::LEGACY_SIZES, true), 404);

    $image = Image::where('name', $filename)->first();
    $format = in_array($request->query('fm'), ImageSupport::modernFormats(), true) ? $request->query('fm') : null;

    $url = $image
      ? $image->url($size, $format)
      : Glide::url($filename, $size, null, $format);

    // Short-lived, so a re-crop reaches anyone who followed it before
    return redirect($url, 301, $this->cacheHeaders(3600));
  }

  protected function respond(string $filename, array $params, int $maxAge, array $cacheDirectives = []): Response
  {
    [$image, $format, $fellBack] = (new RenderAction)->execute($filename, $params);

    abort_if($image === null, 500, 'Image could not be rendered');

    // Cached briefly, so the requested format is tried again soon
    if ($fellBack)
    {
      [$maxAge, $cacheDirectives] = [300, []];
    }

    return response($image, 200, [
      'Content-Type' => 'image/' . ($format === 'jpg' ? 'jpeg' : $format),
    ] + $this->cacheHeaders($maxAge, $cacheDirectives));
  }

  /**
   * Absolute path of an uploaded image; 404 for anything else
   */
  protected function source(string $filename): string
  {
    $path = storage_path('app/public/uploads/' . $filename);
    abort_unless(
      $filename === basename($filename)
        && preg_match('/\.(jpe?g|png)$/i', $filename)
        && is_file($path),
      404
    );

    return $path;
  }

  protected function cacheHeaders(int $maxAge, array $directives = []): array
  {
    return ['Cache-Control' => implode(', ', ['max-age=' . $maxAge, 'public', ...$directives])];
  }
}
