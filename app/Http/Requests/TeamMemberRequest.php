<?php
namespace App\Http\Requests;

/**
 * The slug is set once, on create
 */
class TeamMemberRequest extends AdminRequest
{
  public function rules(): array
  {
    return ($this->isMethod('post') ? ['slug' => 'required|string|max:255|unique:team_members,slug'] : []) + [
      'title' => 'required|string',
      'publish' => 'nullable',
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => 'Titel wird benötigt',
    ];
  }
}
