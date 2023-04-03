<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
  protected $viewPath = 'pages.service.';

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
    $service = Service::with('publishedImages')->first();
    return view($this->viewPath . 'index', ['service' => $service]);
  }
}
