<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Project;
use App\Models\Category;
use App\Models\State;
use App\Models\File;
use App\Models\Image;
use App\Services\Media;
use App\Http\Requests\ProjectStoreRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  
  /**
   * Get a list of projects
   * 
   * @return \Illuminate\Http\Response
   */
  public function get($publish = FALSE)
  {
    if ($publish)
    {
      return new DataCollection(Project::flagged('isPublish')->with('images')->orderBy('order')->orderBy('order', 'ASC')->get());
    }
    return new DataCollection(Project::with('images')->orderBy('order', 'ASC')->get());
  }

  /**
   * Get a single project
   * 
   * @param Project $project
   * @return \Illuminate\Http\Response
   */
  public function find(Project $project)
  {
    $project = Project::with('images', 'categories', 'grids.gridItems.image')->find($project->id);
    $categories = Category::orderBy('title', 'ASC')->get();
    $states = State::orderBy('title', 'ASC')->get();
    return response()->json(['project' => $project, 'categories' => $categories, 'states' => $states]);
  }

  /**
   * Store a newly created project
   *
   * @param  \Illuminate\Http\ProjectStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProjectStoreRequest $request)
  { 
    $project = Project::create(
      array_merge(
        $request->all(), 
        ['slug' => $this->getUniqueSlug($request->input('title'))]
      )
    );
    $project->categories()->attach($request->input('category_ids'));
    $this->handleFlag($project, 'isPublish', $request->input('publish'));
    $this->handleFlag($project, 'hasDetailPage', $request->input('has_detail_page'));
    $this->handleImages($project, $request->input('images'));
    return response()->json(['projectId' => $project->id]);
  }

  /**
   * Update a project for a given project
   *
   * @param Project $project
   * @param  \Illuminate\Http\ProjectStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(Project $project, ProjectStoreRequest $request)
  {
    $project->update($request->all());
    $project->slug = \Str::slug($request->input('title')) != $project->slug ? $this->getUniqueSlug($request->input('title')) : $project->slug;
    $project->save();
    $project->categories()->sync($request->input('category_ids'));
    $this->handleFlag($project, 'isPublish', $request->input('publish'));
    $this->handleFlag($project, 'hasDetailPage', $request->input('has_detail_page'));
    $this->handleImages($project, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Copy an existing project
   * @param Project $project
   * @return \Illuminate\Http\Response
   */
  
  public function copy(Project $project)
  { 
    $project = Project::with('images', 'categories')->find($project->id);
    $newProject = $project->replicate();
    $newProject->title = $project->title . ' Kopie';
    $newProject->slug = $this->getUniqueSlug($newProject->title);
    $newProject->save();
    $newProject->categories()->attach($project->categories->pluck('id'));
    $this->copyImages($newProject);
    $this->handleFlag($newProject, 'isPublish', 0);
    $this->handleFlag($newProject, 'hasDetailPage', $project->hasFlag('hasDetailPage') ? 1 : 0);
    return response()->json('successfully copied');
  }

  /**
   * Toggle the status a given project
   *
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function toggle(Project $project)
  {
    if ($project->hasFlag('isPublish'))
    {
      $project->unflag('isPublish');
    }
    else
    {
      $project->flag('isPublish');
    } 
    return response()->json($project->hasFlag('isPublish'));
  }

  /**
   * Update the order the projects
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $projects = $request->get('projects');
    foreach($projects as $project)
    {
      $p = Project::find($project['id']);
      $p->order = $project['order'];
      $p->save(); 
    }
    return response()->json('successfully updated');
  }

  /**
   * Remove a project
   *
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function destroy(Project $project)
  {
    $project->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle associated images
   *
   * @param Project $project
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Project $project, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $project->id;
      $i->imageable_type = Project::class;
      $i->save();
    }
  }

  /**
   * Copy all images from one project to another
   * 
   * @param Project $project
   */

  protected function copyImages(Project $project)
  {
    foreach($project->images as $image)
    {
      $name = (new Media())->duplicate($image->name);

      // Duplicate the image with the new name
      $newImage = $image->replicate();
      $newImage->name = $name;
      $newImage->imageable_id = $project->id;
      $newImage->imageable_type = Project::class;
      $newImage->save();
    }
  }

  /**
   * Handle flags of a project
   *
   * @param Project $project
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Project $project, $flag, $value)
  {
    if ($value == 1)
    {
      $project->flag($flag);
    }
    else
    {
      $project->unflag($flag);
    }
    return $project->hasFlag($flag);
  }

  private function getUniqueSlug($title)
  {
    $slug = \Str::slug($title);
    $project = Project::withTrashed()->where('slug', $slug)->first();
    if ($project)
    {
      $slug = $slug . '-' . Project::withTrashed()->count();
    }
    return $slug;
  }

}

