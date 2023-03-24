<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Grid;
use App\Models\GridItem;
use Illuminate\Http\Request;

class GridController extends Controller
{

  /**
   * Get a list of grid items
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    $items = Grid::get();
    return response()->json($items);
  }

  /**
   * Store a grid row and create 2 empty items
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $grid = Grid::create([
      'layout' => $request->input('layout'),
      'gridable_type' => "App\Models\\" . $request->input('model.name'),
      'gridable_id' => $request->input('model.id'),
    ]);

    for($i = 0; $i < $request->input('items'); $i++)
    {
      GridItem::create([
        'position' => $i,
        'grid_id' => $grid->id
      ]);
    }

    return response()->json($grid->with('items'));
  }

  /**
   * Update the order of the given grid rows
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $items = $request->get('items');
    foreach($items as $item)
    {
      $i = Grid::find($item['id']);
      $i->order = $item['order'];
      $i->save(); 
    }
    return response()->json('successfully updated');
  }

  /**
   * Remove a grid row with all attached items
   *
   * @param  Grid $grid
   * @return \Illuminate\Http\Response
   */
  public function destroy(Grid $grid)
  {
    $grid = Grid::with('gridItems')->find($grid->id);
    foreach($grid->gridItems as $item)
    {
      $item->delete();
    }
    $grid->delete();
    return response()->json('successfully deleted');
  }
}
