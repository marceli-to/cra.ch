<?php
namespace App\Actions\Image;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

/**
 * Attach uploaded images (`[['id' => 1], ...]`) to their model. Images that
 * are not in the list stay where they are.
 */
class AttachAction
{
  public function execute(Model $owner, array $images): void
  {
    $ids = array_column($images, 'id');

    if ($ids)
    {
      Image::whereKey($ids)->get()->each->update([
        'imageable_id' => $owner->getKey(),
        'imageable_type' => $owner->getMorphClass(),
      ]);
    }
  }
}
