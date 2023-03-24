<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Tasks\StorageCleanup;

class PageController extends BaseController
{
  protected $viewPath = 'pages.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the 'Winterthur im Bild' page
   * 
   * @return \Illuminate\Http\Response
   */

  public function wib()
  {
    $page = Page::with(
      'publishedImage',
      'publishedArticles.publishedImage',
      'publishedArticles.galleries'
    )
    ->where('slug', 'winterthur-im-bild')
    ->get()
    ->first();
    return view($this->viewPath . 'wib.index', ['page' => $page]);
  }
}
