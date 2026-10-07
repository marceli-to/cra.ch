<?php
namespace App\Services;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class ImageResizer
{
  /**
   * Image types that can be resized
   */
  public const EXTENSIONS = ['jpg', 'jpeg', 'png'];

  /**
   * Imagick when available: it keeps the colour profile and doesn't count
   * against PHP's memory_limit. GD drops all metadata.
   */
  public function manager(): ImageManager
  {
    $driver = extension_loaded('imagick') ? new ImagickDriver() : new GdDriver();
    return new ImageManager($driver, autoOrientation: true, strip: false);
  }

  public function driverName(): string
  {
    return extension_loaded('imagick') ? 'imagick' : 'gd';
  }

  /**
   * Width and height as displayed, i.e. after EXIF rotation, read from the
   * header without decoding the image
   *
   * @param string $path
   * @return array|null [width, height]
   */
  public function dimensions(string $path): ?array
  {
    $info = @getimagesize($path);

    if (!$info || !$info[0] || !$info[1])
    {
      return null;
    }

    [$width, $height] = $info;

    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data'))
    {
      $exif = @exif_read_data($path);
      if (in_array($exif['Orientation'] ?? 1, [5, 6, 7, 8]))
      {
        [$width, $height] = [$height, $width];
      }
    }

    return [$width, $height];
  }

  /**
   * Whether the longest side of the image exceeds $maxEdge
   *
   * @param string $path
   * @param int $maxEdge
   * @return bool
   */
  public function exceeds(string $path, int $maxEdge): bool
  {
    $dimensions = $this->dimensions($path);
    return $dimensions && max($dimensions) > $maxEdge;
  }

  /**
   * Scale the image down in place so its longest side is $maxEdge.
   * Written to a temporary file first; the original is only replaced once
   * the new file exists.
   *
   * @param string $path
   * @param int $maxEdge
   * @return array|null [old width, old height, new width, new height], null if nothing to do
   */
  public function resize(string $path, int $maxEdge): ?array
  {
    $before = $this->dimensions($path);

    if (!$before || max($before) <= $maxEdge)
    {
      return null;
    }

    $image = $this->manager()->decodePath($path);

    if ($image->width() >= $image->height())
    {
      $image->scaleDown(width: $maxEdge);
    }
    else
    {
      $image->scaleDown(height: $maxEdge);
    }

    $tmp = dirname($path) . DIRECTORY_SEPARATOR . '.resize_' . basename($path);
    $image->save($tmp, quality: config('images.jpeg_quality'));

    if (!rename($tmp, $path))
    {
      @unlink($tmp);
      throw new \RuntimeException("Could not replace {$path}");
    }

    clearstatcache(true, $path);

    return [$before[0], $before[1], $image->width(), $image->height()];
  }
}
