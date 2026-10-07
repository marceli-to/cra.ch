<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A slot in a grid row: an image (optionally linking to a project or a
 * page), or an article
 */
class GridItem extends Model
{
  protected $fillable = [
    'position',
    'project_id',
    'diary_id',
    'article_id',
    'image_id',
    'grid_id',
    'page',
  ];

  protected $appends = [
    'isArticle',
    'isPage',
    'isDiary',
    'isImage',
    'isProject',
    'caption',
  ];

  /**
   * Load the projects that slots link to (their caption reads them) in a
   * few queries instead of three per slot. Slots without a project stay
   * without the relation, so their array form has no `project` key.
   */
  public static function loadLinkedProjects(iterable $items): void
  {
    (new Collection($items))
      ->filter(fn (GridItem $item) => $item->project_id)
      ->values()
      ->load('project.flags', 'project.categories');
  }

  public function grid(): BelongsTo
  {
    return $this->belongsTo(Grid::class);
  }

  public function image(): BelongsTo
  {
    return $this->belongsTo(Image::class);
  }

  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  public function diary(): BelongsTo
  {
    return $this->belongsTo(Diary::class);
  }

  public function article(): BelongsTo
  {
    return $this->belongsTo(Article::class);
  }

  public function getIsArticleAttribute(): bool
  {
    return $this->article_id && !$this->image_id;
  }

  public function getIsDiaryAttribute(): bool
  {
    return (bool) $this->diary_id;
  }

  public function getIsImageAttribute(): bool
  {
    return (bool) $this->image_id;
  }

  public function getIsProjectAttribute(): bool
  {
    return (bool) $this->project_id;
  }

  /**
   * An image linking to a page (config/pages.php)
   */
  public function getIsPageAttribute(): bool
  {
    return $this->page && $this->image_id;
  }

  /**
   * The project's title, else the image's caption
   */
  public function getCaptionAttribute(): ?string
  {
    if ($this->isProject)
    {
      return $this->project?->title;
    }
    return $this->image?->caption ?: null;
  }

  /**
   * The caption on the site: on the home page the title of the project or
   * page the slot links to, elsewhere the image's caption
   */
  public function captionFor(?string $view): ?string
  {
    if ($view == 'home')
    {
      if ($this->isProject)
      {
        return $this->project?->title;
      }

      if ($this->isPage)
      {
        return config('pages')[$this->page];
      }
    }

    return $this->image?->caption ?: null;
  }
}
