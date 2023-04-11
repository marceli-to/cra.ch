<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Diary;
use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function __construct()
  {
    view()->share('menuProjects', Project::flagged('isPublish')->orderBy('order')->get());
    view()->share('hasDiary', Diary::flagged('isPublish')->first() ? true : false);
  }
}
