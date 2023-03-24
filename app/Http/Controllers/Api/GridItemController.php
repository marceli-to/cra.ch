<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\GridItem;
use App\Http\Requests\GridItemStoreRequest;
use Illuminate\Http\Request;

class GridItemController extends Controller
{
  /**
   * Get a list of grid items
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    $events = GridItem::with('event.image')->orderBy('order')->whereNotNull('event_id')->get();
    $teasers = GridItem::with('teaser.image')->orderBy('order')->whereNotNull('teaser_id')->get();
    $data = ['events' => $events, 'teasers' => $teasers];
    return response()->json($data);
  }

  /**
   * Store a grid item (event)
   *
   * @param  \Illuminate\Http\GridItemStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function storeEvent(GridItemStoreRequest $request)
  {
    $gridItem = GridItem::create(['event_id' => $request->input('id')]);
    $gridItem->save();
    return response()->json(true);
  }

  /**
   * Store a grid item (teaser)
   *
   * @param  \Illuminate\Http\GridItemStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function storeTeaser(GridItemStoreRequest $request)
  {
    $gridItem = GridItem::create(['teaser_id' => $request->input('id')]);
    $gridItem->save();
    return response()->json(true);
  }

  /**
   * Update the order of the given grid item
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $items = $request->get('items');
    foreach($items as $item)
    {
      $i = GridItem::find($item['id']);
      $i->order = $item['order'];
      $i->save(); 
    }
    return response()->json('successfully updated');
  }

  /**
   * Remove a grid item
   *
   * @param  GridItem $gridItem
   * @return \Illuminate\Http\Response
   */
  public function destroy(GridItem $gridItem)
  {
    $gridItem->delete();
    return response()->json('successfully deleted');
  }
}
