<?php
namespace App\Actions\Image;
use App\Models\Image;
use App\Support\Glide;

/**
 * Store the crop chosen in the admin and drop the renditions of the old one
 */
class CropAction
{
  public function execute(Image $image, array $coords): void
  {
    foreach (['coords_w', 'coords_h', 'coords_x', 'coords_y'] as $key)
    {
      $image->$key = round((float) $coords[$key], 12);
    }
    $image->save();

    Glide::forget($image->name);
  }
}
