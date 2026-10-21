<?php
namespace App\Actions\Site;
use App\Models\Diary;
use App\Models\Project;

/**
 * The menu: published projects with a detail page, and whether there is a diary
 */
class GetNavigation
{
  public function execute(): array
  {
    return [
      'menuProjects' => Project::flagged('isPublish')->flagged('hasDetailPage')->orderBy('order')->get(),
      'hasDiary' => Diary::flagged('isPublish')->exists(),
    ];
  }
}
