<?php
namespace App\Models\Concerns;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
  public function images(): MorphMany
  {
    return $this->morphMany(Image::class, 'imageable')->orderBy('order');
  }

  public function publishedImages(): MorphMany
  {
    return $this->images()->where('publish', 1);
  }
}
