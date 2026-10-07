<?php
namespace App\Http\Controllers;
use App\Actions\Site\GetProject;
use App\Actions\Site\GetWorklist;
use App\Models\Project;

class ProjectController extends Controller
{
  public function show(Project $project, GetProject $action)
  {
    // Unpublished projects only for logged-in admins (to preview them)
    abort_unless($project->hasFlag('isPublish') || auth()->user()?->isAdmin(), 404);

    return view('pages.project.show', $action->execute($project));
  }

  public function list(GetWorklist $action)
  {
    return view('pages.project.list', $action->execute());
  }
}
