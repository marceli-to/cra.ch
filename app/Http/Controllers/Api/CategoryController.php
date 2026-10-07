<?php
namespace App\Http\Controllers\Api;
use App\Actions\Category\SaveAction;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\ReorderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\DataCollection;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Project categories (the work list's sections)
 */
class CategoryController extends Controller
{
  public function get()
  {
    return new DataCollection(Category::orderBy('order')->get());
  }

  public function find(Category $category)
  {
    return response()->json($category);
  }

  public function store(CategoryRequest $request)
  {
    $category = (new SaveAction)->execute(new Category, $request->validated('title'));
    return response()->json(['categoryId' => $category->id]);
  }

  public function update(CategoryRequest $request, Category $category)
  {
    (new SaveAction)->execute($category, $request->validated('title'));
    return response()->json('successfully updated');
  }

  public function destroy(Category $category)
  {
    (new DeleteAction)->execute($category);
    return response()->json('successfully deleted');
  }

  public function order(Request $request)
  {
    (new ReorderAction)->execute(Category::class, $this->orderItems($request, 'categories'));
    return response()->json('successfully updated');
  }
}
