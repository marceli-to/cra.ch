<?php
namespace App\Actions\Image;
use App\Models\Image;
use Illuminate\Support\Str;

/**
 * The record of a file that was uploaded before (see UploadAction)
 */
class StoreAction
{
  /**
   * @param array $data validated, `imageable_type` without namespace ('Project')
   */
  public function execute(array $data): Image
  {
    $type = $data['imageable_type'] ?? null;

    return Image::create(array_merge($data, [
      'uuid' => (string) Str::uuid(),
      'imageable_type' => $type ? 'App\\Models\\' . $type : null,
      'imageable_id' => $type ? $data['imageable_id'] : null,
    ]));
  }
}
