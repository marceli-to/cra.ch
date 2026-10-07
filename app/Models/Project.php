<?php
namespace App\Models;
use App\Models\Concerns\HasGrids;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
  use HasGrids, HasImages, HasPublishFlag, SoftDeletes;

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
    'state_id',
  ];

  protected $appends = [
    'abstract',
    'preview',
    'publish',
    'has_detail_page',
    'category_ids',
  ];

  public function state(): BelongsTo
  {
    return $this->belongsTo(State::class);
  }

  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class);
  }

  /**
   * Whether the project has its own page (otherwise only in the work list)
   */
  public function getHasDetailPageAttribute(): int
  {
    return $this->hasFlag('hasDetailPage') ? 1 : 0;
  }

  public function getCategoryIdsAttribute()
  {
    return $this->categories->pluck('id');
  }

  /**
   * The text without tags or line breaks, 200 characters
   */
  public function getAbstractAttribute(): string
  {
    return str_replace("\n", ' ', substr(strip_tags($this->text), 0, 200));
  }

  /**
   * Same as abstract (kept for the admin's data)
   */
  public function getPreviewAttribute(): string
  {
    return $this->abstract;
  }
}
