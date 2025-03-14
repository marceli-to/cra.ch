<?php
namespace App\Filters\Image\Template;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class Thumbnail implements ModifierInterface
{
  protected $size = 300;
  
  public function apply(ImageInterface $image): ImageInterface
  {
    return $image->cover($this->size, $this->size);
  }
}