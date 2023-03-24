<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
// use App\Services\GridItems;
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
    // Get grid items
    // $grid_items = [
    //   'events' => (new GridItems())->get('event'),
    //   'teasers' => (new GridItems())->get('teaser'),
    // ];
    // return view($this->viewPath . 'index', ['hero' => $hero, 'grid_items' => $grid_items]);
    return view($this->viewPath . 'index');
  }

}
