<?php
namespace App\Http\Requests;

class AboutRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'description' => 'required|string',
      'former_employees' => 'nullable|string',
      'cooperation' => 'nullable|string',
      'membership' => 'nullable|string',
    ] + $this->publishAndImages();
  }

  public function messages(): array
  {
    return [
      'description.required' => 'Beschreibung wird benötigt',
    ];
  }
}
