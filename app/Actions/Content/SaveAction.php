<?php
namespace App\Actions\Content;
use App\Actions\Image\AttachAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Create or update a content model: its fields, its flags (e.g.
 * ['isPublish' => true]) and the images to attach (`[['id' => 1], ...]`)
 */
class SaveAction
{
  public function execute(Model $model, array $data, array $flags = [], array $images = []): Model
  {
    return DB::transaction(function () use ($model, $data, $flags, $images) {
      $model->fill($data)->save();

      foreach ($flags as $flag => $on)
      {
        $on ? $model->flag($flag) : $model->unflag($flag);
      }

      (new AttachAction)->execute($model, $images);

      return $model;
    });
  }
}
