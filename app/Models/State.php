<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class State extends Model
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
