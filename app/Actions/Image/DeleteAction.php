<?php
namespace App\Actions\Image;
use App\Models\GridItem;
use App\Models\Image;
use App\Support\Glide;
use Illuminate\Support\Facades\Storage;

/**
 * Delete an image by file name: empty the grid slots that show it, delete
 * the record, its renditions and the file
 */
class DeleteAction
{
  public function execute(string $name): void
  {
    $image = Image::where('name', $name)->first();

    if ($image)
    {
      GridItem::where('image_id', $image->id)->update(['image_id' => null]);
      $image->delete();
    }

    Glide::forget($name);
    foreach (Storage::allDirectories('public') as $directory)
    {
      Storage::delete($directory . '/' . $name);
    }
  }
}
