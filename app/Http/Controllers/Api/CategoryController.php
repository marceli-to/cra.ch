<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  
  /**
   * Get a list of categories
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Category::orderBy('title', 'ASC')->get());
  }

  /**
   * Get a single category
   * 
   * @param Category $category
   * @return \Illuminate\Http\Response
   */
  public function find(Category $category)
  {
    $category = Category::find($category->id);
    return response()->json($category);
  }

  /**
   * Store a newly created category
   *
   * @param  \Illuminate\Http\CategoryStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(CategoryStoreRequest $request)
  { 
    $category = Category::create([
      'title' => $request->input('title'),
      'slug' => \Str::slug($request->input('title')),
    ]);
    return response()->json(['categoryId' => $category->id]);
  }

  /**
   * Update a category for a given category
   *
   * @param Category $category
   * @param  \Illuminate\Http\CategoryStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(Category $category, CategoryStoreRequest $request)
  {
    $category->update([
      'title' => $request->input('title'),
      'slug' => \Str::slug($request->input('title')),
    ]);
    return response()->json('successfully updated');
  }

  /**
   * Remove a category
   *
   * @param  Category $category
   * @return \Illuminate\Http\Response
   */
  public function destroy(Category $category)
  {
    $category->delete();
    return response()->json('successfully deleted');
  }

}

