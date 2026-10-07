<?php
namespace App\Actions\Project;
use App\Actions\Image\DuplicateAction;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

/**
 * Copy a project with its categories and images (as new files), not
 * published; grids are not copied
 */
class CopyAction
{
  public function execute(Project $project): Project
  {
    return DB::transaction(function () use ($project) {
      $copy = $project->replicate();
      $copy->title = $project->title . ' Kopie';
      $copy->slug = (new MakeSlugAction)->execute($copy->title);
      $copy->save();

      $copy->categories()->attach($project->categories->pluck('id'));
      foreach ($project->images as $image)
      {
        (new DuplicateAction)->execute($image, $copy);
      }

      if ($project->hasFlag('hasDetailPage'))
      {
        $copy->flag('hasDetailPage');
      }

      return $copy;
    });
  }
}
