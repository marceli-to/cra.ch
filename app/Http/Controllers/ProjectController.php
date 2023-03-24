<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Category;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
  protected $viewPath = 'pages.project.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show a list of projects
   *
   * @param String $category
   * @return \Illuminate\Http\Response
   */

  public function index($category = NULL)
  { 
    // Get category
    $category = $category ? Category::where('slug', 'like', '%"'.$category.'"%')->firstOrFail() : Category::with('projects')->has('projects')->first();

    // Get projects
    $projects = Project::with('images', 'previewImage', 'categories')->flagged('isPublish')->orderBy('order')->get();

    $projectsByCategory = null;
    if ($category)
    {
      $projectsByCategory = Project::query()->with('images', 'previewImage', 'categories')
      ->whereHas('categories', function ($query) use ($category) {
        $query->where('id', $category->id);
      })->flagged('isPublish')->get();
    }

    // Get project image (overview)
    $projectImage = ProjectImage::with('publishedImage')->find(1);

    return view(
      $this->viewPath . 'index', 
      [
        'projects' => $projects,
        'projectsByCategory' => $projectsByCategory,
        'projectActive' =>  $projectsByCategory, 
        'projectActiveCategory' => $category,
        'projectImage' => $projectImage
      ]
    );
  }

  /**
   * Show a single project by a given project
   *
   * @param String $category
   * @param String $slug
   * @param Project $project
   * @return \Illuminate\Http\Response
   */

  public function show($category = NULL, $slug = NULL, Project $project)
  {
    $category = Category::where('slug', 'like', '%"'.$category.'"%')->firstOrFail();
    $project = Project::with('grids')->findOrFail($project->id);
    return view($this->viewPath . 'show', 
      [
        'category' => $category, 'project' => $project,
        'browse'   => $this->getBrowse($project->id, $category->id),
      ]
    );
  }

  /**
   * Get project browse navigation
   * 
   * @param Integer $projectId
   * @param Integer $categoryId
   * @return Array $items
   */

  protected function getBrowse($projectId = NULL, $categoryId = NULL)
  {
    $projects = Project::query()->with('images', 'categories')
    ->whereHas('categories', function ($query) use ($categoryId) {
      $query->where('id', $categoryId);
    })->orderBy('order')->get();
    
    $keys     = [];
    $items    = [];

    foreach($projects as $p)
    {
      $keys[] = (int) $p->id;
    }

    // Get current key
    $key = array_search($projectId, $keys);

    if ($key == 0)
    {
      $prevId = end($keys);
      $nextId = isset($keys[$key+1]) ? $keys[$key+1] : NULL;
    }
    else if ($key == count($keys) - 1)
    {
      $prevId = $keys[$key-1];
      $nextId = $keys[0];
    }
    else
    {
      $prevId = $keys[$key-1];
      $nextId = $keys[$key+1];
    }

    $items = [
      'prev' => Project::find($prevId),
      'next' => Project::find($nextId),
    ];
    return $items;
  }



}
