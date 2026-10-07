<?php
namespace App\Actions\Site;
use App\Models\Category;

/**
 * All projects by category (also unpublished ones: the list shows every
 * project, only those with a detail page link to it)
 */
class GetWorklist
{
  public function execute(): array
  {
    return [
      'projects' => Category::with('projects.state', 'projects.flags')->orderBy('order')->get(),
    ];
  }
}
