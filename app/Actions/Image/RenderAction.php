<?php
namespace App\Actions\Image;
use App\Support\Glide;
use App\Support\ImageSupport;
use Illuminate\Support\Facades\Log;
use League\Glide\Server;

/**
 * A rendition of an upload through Glide, in the requested modern format if
 * the encoder manages it, else in the upload's own format
 */
class RenderAction
{
  public const FORMAT_QUALITY = ['jpg' => 75, 'png' => 75, 'webp' => 80, 'avif' => 70];

  public function __construct(private ?Server $server = null)
  {
    $this->server ??= Glide::server();
  }

  /**
   * @return array{0: string, 1: string, 2: bool} the image, its format
   *   (jpg, png, webp, avif) and whether it fell back to the upload's format
   *   because the requested one failed; null image if nothing could be rendered
   */
  public function execute(string $filename, array $params): array
  {
    $fallback = $this->sourceFormat($filename);
    $format = in_array($params['fm'] ?? null, ImageSupport::modernFormats(), true) ? $params['fm'] : $fallback;
    $image = $this->render($filename, $params, $format);

    if ($image === null && $format !== $fallback)
    {
      Log::warning("Broken {$format} rendition of {$filename}, served as {$fallback}", $params);
      return [$this->render($filename, $params, $fallback), $fallback, true];
    }

    return [$image, $format, false];
  }

  /**
   * The rendition, or null if it is undecodable twice. A broken one is
   * removed from the cache, so the next request renders it again (oxid saw
   * Imagick write empty AVIFs in forked PHP workers).
   */
  private function render(string $filename, array $params, string $format): ?string
  {
    $params['fm'] = $format;
    $params['q'] = self::FORMAT_QUALITY[$format];

    for ($attempt = 1; $attempt <= 2; $attempt++)
    {
      $cachedPath = $this->server->makeImage('uploads/' . $filename, $params);
      $image = $this->server->getCache()->read($cachedPath);

      if (@getimagesizefromstring($image))
      {
        return $image;
      }

      $this->server->getCache()->delete($cachedPath);
    }

    return null;
  }

  /**
   * A PNG stays a PNG (it may have transparency)
   */
  private function sourceFormat(string $filename): string
  {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'png' ? 'png' : 'jpg';
  }
}
