<?php
namespace App\Actions\Site;
use App\Models\Project;

/**
 * A project page with the previous and next project (published, with a
 * detail page, in their order)
 */
class GetProject
{
  public function execute(Project $project): array
  {
    return [
      'project' => $project->load('grids.gridItems.image'),
      'browse' => $this->browse($project),
      'og_image' => null,
    ];
  }

  /**
   * Neighbours in the list, wrapping around at both ends. A project that
   * is not in the list (an admin's preview, no detail page) sits between
   * the last and the first.
   */
  private function browse(Project $project): array
  {
    $projects = Project::flagged('isPublish')->flagged('hasDetailPage')->orderBy('order')->get();
    $index = $projects->search(fn (Project $p) => $p->is($project));

    if ($index === false)
    {
      return ['prev' => $projects->last(), 'next' => $projects->first()];
    }

    $count = $projects->count();

    return [
      'prev' => $projects->get(($index - 1 + $count) % $count),
      'next' => $projects->get(($index + 1) % $count),
    ];
  }
}
