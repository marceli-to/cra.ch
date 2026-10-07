<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\ReorderAction;
use App\Actions\Content\ToggleFlagAction;
use App\Actions\Project\CopyAction;
use App\Actions\Project\SaveAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\DataCollection;
use App\Models\Category;
use App\Models\GridItem;
use App\Models\Project;
use App\Models\State;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  /**
   * All projects, or only the published ones, in their order
   */
  public function get($publish = false)
  {
    $projects = Project::with('images', 'categories', 'flags')
      ->when($publish, fn ($query) => $query->flagged('isPublish'))
      ->orderBy('order')
      ->get();

    return new DataCollection($projects);
  }

  /**
   * The project with what its form offers
   */
  public function find(Project $project)
  {
    $project->load('images', 'categories', 'grids.gridItems.image');
    GridItem::loadLinkedProjects($project->grids->flatMap->gridItems);

    return response()->json([
      'project' => $project,
      'categories' => Category::orderBy('title')->get(),
      'states' => State::orderBy('title')->get(),
    ]);
  }

  public function store(ProjectRequest $request)
  {
    $project = (new SaveAction)->execute(new Project, $request->fields(), $request->input('category_ids', []), $this->flags($request), $request->input('images', []));
    return response()->json(['projectId' => $project->id]);
  }

  public function update(ProjectRequest $request, Project $project)
  {
    (new SaveAction)->execute($project, $request->fields(), $request->input('category_ids', []), $this->flags($request), $request->input('images', []));
    return response()->json('successfully updated');
  }

  public function copy(Project $project)
  {
    (new CopyAction)->execute($project);
    return response()->json('successfully copied');
  }

  public function toggle(Project $project)
  {
    return response()->json((new ToggleFlagAction)->execute($project));
  }

  public function order(Request $request)
  {
    (new ReorderAction)->execute(Project::class, $this->orderItems($request, 'projects'));
    return response()->json('successfully updated');
  }

  public function destroy(Project $project)
  {
    (new DeleteAction)->execute($project);
    return response()->json('successfully deleted');
  }

  private function flags(ProjectRequest $request): array
  {
    return [
      'isPublish' => $request->boolean('publish'),
      'hasDetailPage' => $request->boolean('has_detail_page'),
    ];
  }
}
