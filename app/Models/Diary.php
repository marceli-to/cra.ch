<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Diary extends Model
{
  use HasFlags;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'title',
    'description',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'publish',
    'articleContent',
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
   * Get the article attribute
   */

  public function getArticleContentAttribute()
  {
    $article = '<article>';
    if ($this->title)
    {
      $article .= '<h1>' . $this->title . '</h1>';
    }
    if ($this->description)
    {
      $article .= $this->description;
    }
    $article .= '</article>';
    return $article;
  }

}
