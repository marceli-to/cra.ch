<?php
namespace App\Models\Concerns;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

/**
 * Flags (spatie/laravel-model-flags) and a `publish` attribute (0/1, the
 * isPublish flag); models list it in $appends
 */
trait HasPublishFlag
{
  use HasFlags;

  public function initializeHasPublishFlag(): void
  {
    $this->makeHidden('flags');
  }

  /**
   * From eager-loaded flags (`with('flags')`) without a query
   */
  public function hasFlag(string|\BackedEnum $name): bool
  {
    $name = $this->enumValue($name);

    return $this->relationLoaded('flags')
      ? $this->flags->contains('name', $name)
      : $this->flags()->where('name', $name)->exists();
  }

  public function flag(string|\BackedEnum $name): self
  {
    $this->flags()->firstOrCreate(['name' => $this->enumValue($name)])->touch();
    $this->unsetRelation('flags');

    return $this;
  }

  public function unflag(string|\BackedEnum|array $name): self
  {
    $names = array_map(fn ($flag) => $this->enumValue($flag), (array) $name);
    $this->flags()->whereIn('name', $names)->delete();
    $this->unsetRelation('flags');

    return $this;
  }

  public function getPublishAttribute(): int
  {
    return $this->hasFlag('isPublish') ? 1 : 0;
  }
}
