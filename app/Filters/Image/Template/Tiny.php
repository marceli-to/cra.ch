<?php
namespace App\Filters\Image\Template;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Tiny implements FilterInterface
{
  protected $size = 100;
  
  public function applyFilter(Image $image)
  {
    return $image->fit($this->size);
  }
}