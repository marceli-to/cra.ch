<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
  use SoftDeletes, HasFlags;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'title',
    'slug',
    'text',
    'text_services',
    'text_info',
    'type',
    'location',
    'periode',
    'order',
    'state_id'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'abstract',
    'publish',
    'worklist',
    'category_ids'
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

  public function image()
  {
    return $this->morphOne(Image::class, 'imageable');
  }

  public function publishedImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1);
  }

  public function previewImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('preview', 1);
  }

  public function images()
  {
    return $this->morphMany(Image::class, 'imageable')->orderBy('order');
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  public function grids()
  {
    return $this->morphMany(Grid::class, 'gridable')->orderBy('order');
  }

  /**
   * The categories that belong to this project.
   */
  
  public function categories()
  {
    return $this->belongsToMany(Category::class);
  }

  /**
   * Get the publish attribute
   * 
   */

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublish') ? 1 : 0;    
  }

    /**
   * Get the publish attribute
   * 
   */

  public function getWorklistAttribute()
  {
    return $this->hasFlag('isWorklist') ? 1 : 0;    
  }

  /**
   * Get array of ids from the m:n topic relationship
   *
   */

  public function getCategoryIdsAttribute()
  {
    return $this->categories->pluck('id');
  }

  /**
   * Get the abstract attribute
   * 
   */

  public function getAbstractAttribute()
  {
    // remove all html tags, shorten to 200 chars and remove line breaks
    return str_replace("\n", ' ', substr(strip_tags($this->text), 0, 200));
  }
}
