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
    'linkText'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

   protected $appends = [
    'publish',
    'displayTitle',
    'articleContent',
  ];

  /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  |
  |
  */

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

  /**
   * Get the article content attribute
   */

  public function getArticleContentAttribute()
  {
    $article = '<article class="teaser">';
    if ($this->date)
    {
      $article .= '<div class="teaser__date">' . $this->date . '</div>';
    } 

    if ($this->title)
    {
      $article .= '<h2>' . $this->title . '</h2>';
    }

    if ($this->text)
    {
      $article .= $this->text;
    }

    if ($this->link)
    {
      $linkText = $this->linkText ? $this->linkText : "Mehr";
      $article .= '
        <div class="teaser__link">
          <a href="' . $this->link . '" target="_blank" title="' . $linkText . '">
            ' .  $linkText . '
          </a>
        </div>
      ';
    }  

    $article .= '</article>';
    return $article;
  }

}
