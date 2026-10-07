<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\ReorderAction;
use App\Actions\Grid\DeleteRowAction;
use App\Actions\Grid\StoreRowAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GridRowRequest;
use App\Models\Grid;
use Illuminate\Http\Request;

/**
 * Rows of the image grids (home, projects, diary)
 */
class GridController extends Controller
{
  public function store(GridRowRequest $request)
  {
    $grid = (new StoreRowAction)->execute($request->owner(), $request->validated('layout'), $request->validated('items'));
    return response()->json($grid->load('gridItems'));
  }

  public function order(Request $request)
  {
    (new ReorderAction)->execute(Grid::class, $this->orderItems($request, 'items'));
    return response()->json('successfully updated');
  }

  public function destroy(Grid $grid)
  {
    (new DeleteRowAction)->execute($grid);
    return response()->json('successfully deleted');
  }
}
