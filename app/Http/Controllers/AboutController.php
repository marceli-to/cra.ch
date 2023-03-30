<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Diary;
use Illuminate\Http\Request;

class AboutController extends BaseController
{
  protected $viewPath = 'pages.about.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the homepage
   *
   * @return \Illuminate\Http\Response
   */

  public function diary()
  {
    $grid = Diary::with('grids.gridItems.image')->flagged('isPublish')->first();
    return view($this->viewPath . 'diary', ['grid' => $grid]);
  }

}
