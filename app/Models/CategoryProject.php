<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryProject extends Pivot
{
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'project_id',
    'category_id'
  ];
}
