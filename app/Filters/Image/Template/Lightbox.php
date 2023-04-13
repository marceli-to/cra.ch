<?php
namespace App\Filters\Image\Template;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Lightbox implements FilterInterface
{
  /**
   * Maximum width for large landscape images
   */    
  protected $max_width = 2000;    

  /**
   * Maximum height for large portrait images
   */    
  protected $max_height = 1500;


  public function applyFilter(Image $image)
  {
    // Get width and height
    $width  = $image->getWidth();
    $height = $image->getHeight();

    // Resize landscape image
    if ($width > $height && $width >= $this->max_width)
    {
      $image->resize($this->max_width, null, function ($constraint) {
        return $constraint->aspectRatio();
      });
    }
    else if ($height >= $this->max_height)
    {
      $image->resize(null, $this->max_height, function ($constraint) {
        return $constraint->aspectRatio();
      });
    }
    return $image;
  }
}