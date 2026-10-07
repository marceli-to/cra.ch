<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\GridItem;
use App\Models\Home;

class HomeController extends Controller
{
  /**
   * The home page's grid
   */
  public function find()
  {
    $home = Home::with('grids.gridItems.image', 'grids.gridItems.article')->find(1);
    GridItem::loadLinkedProjects($home?->grids->flatMap->gridItems ?? []);

    return response()->json(['home' => $home]);
  }
}
