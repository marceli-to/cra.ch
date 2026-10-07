<?php
namespace App\Http\Requests;

class ProjectRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'title' => 'required|string|max:255',
      'text' => 'nullable|string',
      'text_services' => 'nullable|string',
      'text_info' => 'nullable|string',
      'type' => 'nullable|string|max:255',
      'location' => 'nullable|string|max:255',
      'periode' => 'nullable|string|max:255',
      'state_id' => 'nullable|integer|exists:states,id',
      'category_ids' => 'nullable|array',
      'category_ids.*' => 'integer|exists:categories,id',
      'has_detail_page' => 'nullable',
    ] + $this->publishAndImages();
  }

  public function messages(): array
  {
    return [
      'title.required' => 'Titel wird benötigt',
    ];
  }

  public function fields(): array
  {
    return collect(parent::fields())->except(['category_ids', 'has_detail_page'])->all();
  }
}
