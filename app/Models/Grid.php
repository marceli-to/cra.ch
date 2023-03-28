<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grid extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'layout',
    'order',
    'gridable_id',
    'gridable_type'
  ];
  
  /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  |
  |
  */

  public function gridItems()
  {
    return $this->hasMany(GridItem::class);
  }

}
