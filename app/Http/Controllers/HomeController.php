<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
  protected $viewPath = 'pages.home.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the homepage
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $grid = Home::with(
      'grids.gridItems.image', 
      'grids.gridItems.project', 
      'grids.gridItems.article'
    )->find(1);
    return view($this->viewPath . 'index', ['grid' => $grid]);
  }

}
