<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use Illuminate\Http\Request;

class WorklistController extends BaseController
{
  protected $viewPath = 'pages.worklist.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the worklist page
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $projects = Project::with('previewImage')->flagged('isPublish')->flagged('isWorklist')->get();
    return view($this->viewPath . 'index', ['projects' => $projects]);
  }

}
