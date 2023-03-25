<?php
namespace App\Models;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'title',
    'slug',
  ];

  /**
   * The courses that belong to this category
   */
  
  public function projects()
  {
    return $this->belongsToMany(Project::class);
  }

}
