<?php
namespace App\Actions\Grid;
use App\Models\Grid;
use Illuminate\Support\Facades\DB;

class DeleteRowAction
{
  public function execute(Grid $grid): void
  {
    DB::transaction(function () use ($grid) {
      $grid->gridItems()->delete();
      $grid->delete();
    });
  }
}
