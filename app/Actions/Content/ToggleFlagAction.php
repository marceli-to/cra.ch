<?php
namespace App\Actions\Content;
use Illuminate\Database\Eloquent\Model;

/**
 * Switch a model flag (spatie/laravel-model-flags) on or off; returns the new state
 */
class ToggleFlagAction
{
  public function execute(Model $model, string $flag = 'isPublish'): bool
  {
    $model->hasFlag($flag) ? $model->unflag($flag) : $model->flag($flag);

    return $model->hasFlag($flag);
  }
}
