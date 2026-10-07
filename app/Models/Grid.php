<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * A row of an image grid; `layout` names the row's template
 * (components/galleries/gallery-{layout})
 */
class Grid extends Model
{
  protected $fillable = [
    'layout',
    'order',
    'gridable_id',
    'gridable_type',
  ];

  public function gridable(): MorphTo
  {
    return $this->morphTo();
  }

  public function gridItems(): HasMany
  {
    return $this->hasMany(GridItem::class);
  }
}
