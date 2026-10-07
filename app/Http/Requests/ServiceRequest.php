<?php
namespace App\Http\Requests;

class ServiceRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'column_one' => 'nullable|string|required_without_all:column_two',
      'column_two' => 'nullable|string|required_without_all:column_one',
    ] + $this->publishAndImages();
  }

  public function messages(): array
  {
    return [
      'column_one.required_without_all' => 'Projekt- und Bauleitung wird benötigt',
      'column_two.required_without_all' => 'Leistungen, Referenzen wird benötigt',
    ];
  }
}
