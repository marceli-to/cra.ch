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
      // Clamped to the slot by resources/js/web/modules/teaser.js
      $article .= '<div class="teaser__text" data-teaser-text>' . $this->text . '</div>';
    }

    if ($this->link)
    {
      $linkText = $this->linkText ? $this->linkText : "Mehr";
      // A bare "Mehr" says nothing out of context (screen readers, crawlers):
      // add the title, visible to them only
      $context = !$this->linkText && $this->title
        ? '<span class="visually-hidden">: ' . strip_tags($this->title) . '</span>'
        : '';
      $article .= '
        <div class="teaser__link">
          <a href="' . $this->link . '"' . ($this->isExternalLink() ? ' target="_blank"' : '') . '>' . $linkText . $context . '</a>
        </div>
      ';
    }

    $article .= '</article>';
    return $article;
  }

  /**
   * Links to another site open in a new tab; "/leistungen" or
   * "https://cristinarutz.ch/leistungen" stay in this one
   */
  private function isExternalLink(): bool
  {
    $host = parse_url($this->link, PHP_URL_HOST);
    if (!$host)
    {
      return false;
    }
    $strip = fn (string $h) => preg_replace('/^www\./', '', strtolower($h));
    $own = [$strip(request()->getHost()), $strip((string) parse_url(config('app.url'), PHP_URL_HOST))];
    return !in_array($strip($host), $own, true);
  }
}
