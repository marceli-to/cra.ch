<?php
namespace App\Actions\Content;
use Illuminate\Database\Eloquent\Model;

/**
 * Switch a 0/1 column (e.g. images.publish); returns the new value
 */
class ToggleAttributeAction
{
  public function execute(Model $model, string $attribute): int
  {
    $model->$attribute = $model->$attribute ? 0 : 1;
    $model->save();

    return $model->$attribute;
  }
}
