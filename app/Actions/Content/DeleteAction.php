<?php
namespace App\Actions\Content;
use Illuminate\Database\Eloquent\Model;

class DeleteAction
{
  public function execute(Model $model): void
  {
    $model->delete();
  }
}
