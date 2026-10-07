<?php
namespace App\Http\Requests;

class ArticleRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'date' => 'nullable|string|max:255',
      'title' => 'nullable|string|max:255',
      'text' => 'required|string',
      'link' => 'nullable|string|max:255',
      'linkText' => 'nullable|string|max:255',
      'publish' => 'nullable',
    ];
  }

  public function messages(): array
  {
    return [
      'text.required' => 'Text wird benötigt',
    ];
  }
}
