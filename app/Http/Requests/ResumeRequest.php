<?php
namespace App\Http\Requests;

class ResumeRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'periode' => 'required|string|max:255',
      'description' => 'required|string',
      'publish' => 'nullable',
    ] + ($this->isMethod('post') ? ['team_member_id' => 'required|integer|exists:team_members,id'] : []);
  }

  public function messages(): array
  {
    return [
      'periode.required' => 'Zeitraum wird benötigt',
      'description.required' => 'Beschreibung wird benötigt',
    ];
  }
}
