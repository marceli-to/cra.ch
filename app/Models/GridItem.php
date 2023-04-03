<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GridItem extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'position',
    'project_id',
    'diary_id',
    'article_id',
    'image_id',
    'grid_id'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

   protected $appends = [
    'isArticle',
    'isDiary',
    'isImage',
    'isProject',
    'caption',
  ];

  /**
   * The grid that belongs to this image grid item.
   */
  
  public function grid()
  {
    return $this->belongsTo(Grid::class);
  }

  /**
   * The image that belongs to this image grid item.
   */
  
  public function image()
  {
    return $this->belongsTo(Image::class);
  }

  /**
   * The published image that belongs to this image grid item.
   */
  
  public function publishedImage()
  {
    return $this->belongsTo(Image::class);
  }

  /**
   * The project that belongs to this image grid item.
   */
  
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * The diary that belongs to this image grid item.
   */
  
  public function diary()
  {
    return $this->belongsTo(Diary::class);
  }

  /**
   * The article that belongs to this image grid item.
   */
  
  public function article()
  {
    return $this->belongsTo(Article::class);
  }

  /**
   * The published article that belongs to this image grid item.
   */
  
   public function publishedArticle()
   {
     return $this->belongsTo(Article::class)->flagged('isPublish');
   }

  /**
   * Get the isArticle attribute.
   * @return bool
   */

  public function getIsArticleAttribute()
  {
    return $this->article_id && !$this->image_id ? true : false;
  }

  /**
   * Get the isDiary attribute.
   * @return bool
   */

  public function getIsDiaryAttribute()
  {
    return $this->diary_id ? true : false;
  }

  /**
   * Get the isImage attribute.
   * @return bool
   */
  
  public function getIsImageAttribute()
  {
    return $this->image_id ? true : false;
  }

  /**
   * Get the isProject attribute.
   * @return bool
   */

  public function getIsProjectAttribute()
  {
    return $this->project_id ? true : false;
  }

  /**
   * Get the caption attribute.
   * @return string
   */

  public function getCaptionAttribute()
  {
    if ($this->isProject)
    {
      return $this->project->title;
    }
    return $this->image && $this->image->caption ? $this->image->caption : null;
  }
}
