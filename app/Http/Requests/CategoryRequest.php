<?php
namespace App\Http\Requests;

class CategoryRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'title' => 'required|string',
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => 'Titel wird benötigt',
    ];
  }
}
