<?php
namespace App\Actions\Grid;
use App\Models\Grid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Append a row with the given layout and its empty slots to a model's grid
 */
class StoreRowAction
{
  public function execute(Model $owner, string $layout, int $slots): Grid
  {
    return DB::transaction(function () use ($owner, $layout, $slots) {
      $grid = $owner->grids()->create(['layout' => $layout]);

      for ($position = 0; $position < $slots; $position++)
      {
        $grid->gridItems()->create(['position' => $position]);
      }

      return $grid;
    });
  }
}
