<?php
namespace App\Http\Requests;

class DiaryRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'description' => 'required|string',
    ] + $this->publishAndImages();
  }

  public function messages(): array
  {
    return [
      'description.required' => 'Beschreibung wird benötigt',
    ];
  }
}
