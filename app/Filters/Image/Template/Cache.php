<?php
namespace App\Filters\Image\Template;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class Cache implements ModifierInterface
{
  protected $maxSize;
  protected $coords = FALSE;
  protected $hasCrop = FALSE;
  protected $cropWidth = NULL;
  protected $cropHeight = NULL;
  protected $cropX = NULL;
  protected $cropY = NULL;
  protected $ratio;
  protected $orientation = 'landscape';

  public function __construct($maxSize = NULL, $coords = FALSE, $ratio = NULL)
  {
    $this->maxSize   = $maxSize ? $maxSize : 1500;
    $this->coords    = $coords;
    $this->ratio     = $ratio;
  }

  public function apply(ImageInterface $image): ImageInterface
  {
    // Get image orientation based on image width/height
    $width  = $image->width();
    $height = $image->height();
    if ($height >= $width)
    {
      $this->orientation = 'portrait';
    }

    // Set crop variables && overwrite image orientation if needed
    if ($this->coords && $this->coords != '0,0,0,0')
    {
      list($this->cropWidth, $this->cropHeight, $this->cropX, $this->cropY) = explode(',', $this->coords);
      $this->orientation = $this->cropHeight >= $this->cropWidth ? 'portrait' : 'landscape';
      $this->hasCrop = TRUE;
    }

    if ($this->hasCrop)
    {
      // First crop the image to the exact coordinates
      $image = $image->crop(
        floor($this->cropWidth), 
        floor($this->cropHeight), 
        floor($this->cropX), 
        floor($this->cropY)
      );
      
      // Then scale down to maxSize while maintaining aspect ratio
      if ($this->orientation == 'landscape')
      {
        return $image->scaleDown(width: $this->maxSize);
      }
      else
      {
        return $image->scaleDown(height: $this->maxSize);
      }
    }
    else
    {
      // For non-cropped images, use scaleDown() to maintain aspect ratio and prevent upscaling
      if ($this->orientation == 'landscape')
      {
        return $image->scaleDown(width: $this->maxSize);
      }
      else
      {
        return $image->scaleDown(height: $this->maxSize);
      }
    }
  }
}