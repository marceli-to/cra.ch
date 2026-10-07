<?php
namespace App\Models\Concerns;
use App\Models\Grid;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Rows of the image grid (see Grid, GridItem)
 */
trait HasGrids
{
  public function grids(): MorphMany
  {
    return $this->morphMany(Grid::class, 'gridable')->orderBy('order');
  }
}
