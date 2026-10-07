<?php
namespace App\Models;
use App\Models\Concerns\HasGrids;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;

/**
 * A teaser on the home page grid
 */
class Article extends Model
{
  use HasGrids, HasPublishFlag;

  protected $fillable = [
    'date',
    'title',
    'text',
    'link',
    'linkText',
  ];

  protected $appends = [
    'publish',
    'displayTitle',
    'articleContent',
  ];

  /**
   * "date • title", or the start of the text (admin lists)
   */
  public function getDisplayTitleAttribute(): string
  {
    if ($this->title)
    {
      return $this->date ? $this->date . ' &bull; ' . $this->title : $this->title;
    }
    return substr(strip_tags($this->text), 0, 25) . '...';
  }

  /**
   * The teaser as it appears in a grid slot
   */
  public function getArticleContentAttribute(): string
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
