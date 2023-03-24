<?php
namespace App\Filters\Image\Template;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Cache implements FilterInterface
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
    $this->ratio     = $ratio ? $ratio : '3x2';
  }

  public function applyFilter(Image $image)
  {
    // Get image orientation based on image width/height
    $width  = $image->getWidth();
    $height = $image->getHeight();
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
      if ($this->orientation == 'landscape')
      {
        return $image->crop(floor($this->cropWidth), floor($this->cropHeight), floor($this->cropX), floor($this->cropY))
          ->resize($this->maxSize, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
      }
      else
      {
        return $image->crop(floor($this->cropWidth), floor($this->cropHeight), floor($this->cropX), floor($this->cropY))
          ->resize(null, $this->maxSize, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
      }
    }
    else
    {
      if ($this->ratio)
      {
        $ratio = explode('x', $this->ratio);

        if ($ratio[0] > $ratio[1])
        {
          $x = $this->maxSize;
          $y = $this->maxSize / $ratio[0] * $ratio[1];
        }
        else
        {
          $y = $this->maxSize;
          $x = $this->maxSize / $ratio[1] * $ratio[0];
        }

        // Landscape
        if ($this->orientation == 'landscape')
        {
          return $image->fit(floor($x), floor($y), function ($constraint) {
            $constraint->upsize();
          });
        }

        // Portrait (switch x and y)
        return $image->fit(floor($y), floor($x), function ($constraint) {
          $constraint->upsize();
        });

      }

      return $image->fit($this->maxSize, $this->maxSize, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
    }
  }
}