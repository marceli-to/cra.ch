<?php
namespace App\Http\Requests;

class GridItemRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'id' => 'required|integer|exists:grid_items,id',
      'position' => 'required|integer|min:0',
      'image_id' => 'nullable|integer|exists:images,id',
      'project_id' => 'nullable|integer|exists:projects,id',
      'diary_id' => 'nullable|integer|exists:diaries,id',
      'article_id' => 'nullable|integer|exists:articles,id',
      'page' => 'nullable|string|in:' . implode(',', array_keys(config('pages'))),
    ];
  }
}
