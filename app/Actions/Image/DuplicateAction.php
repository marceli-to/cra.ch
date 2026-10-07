<?php
namespace App\Actions\Image;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Copy an image (file and record) for another model, e.g. a copied project
 */
class DuplicateAction
{
  public function execute(Image $image, Model $owner): Image
  {
    // A new unique id in front of the original name
    $name = uniqid() . '_' . substr($image->name, strpos($image->name, '_') + 1);
    Storage::copy('public/uploads/' . $image->name, 'public/uploads/' . $name);

    $copy = $image->replicate();
    $copy->fill([
      'uuid' => (string) Str::uuid(),
      'name' => $name,
      'imageable_id' => $owner->getKey(),
      'imageable_type' => $owner->getMorphClass(),
    ])->save();

    return $copy;
  }
}
