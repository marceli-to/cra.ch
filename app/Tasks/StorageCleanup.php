<?php
namespace App\Tasks;
use App\Models\File;
use App\Models\Image;
use App\Services\Media;

class StorageCleanup
{
  public function run()
  {
    $images = Image::onlyTrashed()->limit(50)->get();
    if ($images->count() > 0)
    {
      foreach($images as $image)
      {
        (new Media())->remove($image->name);
        $image->forceDelete();
      }
    }
  }
}