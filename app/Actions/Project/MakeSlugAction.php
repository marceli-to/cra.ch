<?php
namespace App\Actions\Project;
use App\Models\Project;
use Illuminate\Support\Str;

/**
 * The title's slug; if a project (also a deleted one) has it already,
 * with the number of projects appended
 */
class MakeSlugAction
{
  public function execute(string $title): string
  {
    $slug = Str::slug($title);

    return Project::withTrashed()->where('slug', $slug)->exists()
      ? $slug . '-' . Project::withTrashed()->count()
      : $slug;
  }
}
