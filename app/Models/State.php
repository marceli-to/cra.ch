<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class State extends Base
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
   protected $fillable = [
    'title',
  ];

  /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  |
  |
  */

  public function projects()
  {
    return $this->hasMany(Project::class);
  }
}
