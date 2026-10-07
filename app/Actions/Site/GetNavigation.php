<?php
namespace App\Actions\Site;
use App\Models\Diary;
use App\Models\Project;

/**
 * The menu: published projects, and whether there is a diary
 */
class GetNavigation
{
  public function execute(): array
  {
    return [
      'menuProjects' => Project::with('flags')->flagged('isPublish')->orderBy('order')->get(),
      'hasDiary' => Diary::flagged('isPublish')->exists(),
    ];
  }
}
