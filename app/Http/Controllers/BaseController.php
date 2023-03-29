<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function __construct()
  {
    view()->share('menuProjects', Project::flagged('isPublish')->get());
  }
}
