<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  /**
   * Get the homepage
   * 
   * @return \Illuminate\Http\Response
   */
  public function find()
  {
    $home = Home::with('grids.gridItems.image')->find(1);
    return response()->json(['home' => $home]);
  }

}

