<?php
namespace App\Http\Controllers\Api;
use App\Actions\Grid\ResetItemAction;
use App\Actions\Grid\SetItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GridItemRequest;
use App\Models\GridItem;

/**
 * The slots of a grid row
 */
class GridItemController extends Controller
{
  public function store(GridItemRequest $request)
  {
    $item = (new SetItemAction)->execute(GridItem::findOrFail($request->validated('id')), $request->validated());
    return response()->json($item);
  }

  public function reset(GridItem $gridItem)
  {
    (new ResetItemAction)->execute($gridItem);
    return response()->json('successfully deleted');
  }
}
