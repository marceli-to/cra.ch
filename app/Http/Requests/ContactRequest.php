<?php
namespace App\Http\Requests;

class ContactRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'address' => 'required|string',
      'description' => 'nullable|string',
      'maps_uri' => 'nullable|string',
      'imprint' => 'nullable|string',
    ] + $this->publishAndImages();
  }

  public function messages(): array
  {
    return [
      'address.required' => 'Adresse wird benötigt',
    ];
  }
}
