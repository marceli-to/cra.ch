<?php
namespace App\Actions\Category;
use App\Models\Category;
use Illuminate\Support\Str;

/**
 * Create or update a category; the slug follows the title
 */
class SaveAction
{
  public function execute(Category $category, string $title): Category
  {
    $category->fill(['title' => $title, 'slug' => Str::slug($title)])->save();

    return $category;
  }
}
