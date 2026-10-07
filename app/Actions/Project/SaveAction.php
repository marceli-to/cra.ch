<?php
namespace App\Actions\Project;
use App\Actions\Content\SaveAction as SaveContentAction;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Create or update a project: fields, slug (made from the title when the
 * project is new or its title changes), categories, flags and images
 */
class SaveAction
{
  public function execute(Project $project, array $data, array $categoryIds, array $flags, array $images): Project
  {
    return DB::transaction(function () use ($project, $data, $categoryIds, $flags, $images) {
      if (!$project->exists || Str::slug($data['title']) !== Str::slug($project->title))
      {
        $data['slug'] = (new MakeSlugAction)->execute($data['title']);
      }

      (new SaveContentAction)->execute($project, $data, $flags, $images);
      $project->categories()->sync($categoryIds);

      return $project;
    });
  }
}
