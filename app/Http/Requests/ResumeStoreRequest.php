<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ResumeStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */

  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */

  public function rules()
  {
    return [
      'periode' => 'required',
      'description' => 'required',
    ];
  }

  /**
   * Custom message for validation
   *
   * @return array
   */
  
  public function messages()
  {
    return [
      'periode.required' => [
        'field' => 'periode',
        'error' => 'Zeitraum wird benötigt'
      ],
      'description.required' => [
        'field' => 'periode',
        'error' => 'Beschreibung wird benötigt'
      ],
    ];
  }
}