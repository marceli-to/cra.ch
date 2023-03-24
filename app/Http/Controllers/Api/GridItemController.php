<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\GridItem;
use Illuminate\Http\Request;

class GridItemController extends Controller
{

  /**
   * Store a grid item
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request)
  {
    $gridItem = GridItem::find($request->input('id'));
    $gridItem->image_id = $request->input('image_id') ? $request->input('image_id') : NULL;
    $gridItem->project_id = $request->input('project_id') ? $request->input('project_id') : NULL;
    $gridItem->position = $request->input('position');
    $gridItem->save();
    return response()->json($gridItem);
  }

  /**
   * Reset a grid item
   *
   * @param  GridItem $gridItem
   * @return \Illuminate\Http\Response
   */
  public function reset(GridItem $gridItem)
  {
    $gridItem->image_id = NULL;
    $gridItem->project_id = NULL;
    $gridItem->save();
    return response()->json('successfully deleted');
  }

}
