<?php
namespace App\Http\Controllers;
use App\Actions\Site\GetHome;

class HomeController extends Controller
{
  public function index(GetHome $action)
  {
    return view('pages.home.index', $action->execute());
  }
}
