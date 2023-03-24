<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Grid extends Base
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
