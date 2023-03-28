<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Article extends Model
{
  use HasFlags;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
  protected $fillable = [
    'date',
    'title',
    'text',
    'link',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

   protected $appends = [
    'publish',
    'displayTitle',
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

  public function grids()
  {
    return $this->morphMany(Grid::class, 'gridable')->orderBy('order');
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
   * Get the displayTitle attribute
   * 
   */

   public function getDisplayTitleAttribute()
   {
    if ($this->title)
    {
      return $this->date ? $this->date . ' &bull; ' . $this->title : $this->title;
    } 
    return substr(strip_tags($this->text), 0, 25) . '...';
   }

}
