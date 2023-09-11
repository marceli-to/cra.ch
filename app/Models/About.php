<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class About extends Model
{
  use HasFlags;

  protected $table = 'about';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'title',
    'description',
    'cooperation',
    'employees',
    'membership',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'publish',
  ];

  /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  |
  |
  */

  /**
   * The images that belong to this model.
   */

  public function images()
  {
    return $this->morphMany(Image::class, 'imageable')->orderBy('order');
  }

  public function publishedImages()
  {
    return $this->morphMany(Image::class, 'imageable')->where('publish', 1)->orderBy('order');
  }

  /**
   * Get the publish attribute
   * 
   */

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublish') ? 1 : 0;    
  }

}
