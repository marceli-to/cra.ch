<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
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
      'column_one' => 'required_without_all:column_two',
      'column_two' => 'required_without_all:column_one',
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
      'column_one.required_without_all' => [
        'field' => 'column_one',
        'error' => 'Projekt- und Bauleitung'
      ],
      'column_one.required_without_all' => [
        'field' => 'column_two',
        'error' => 'Leistungen, Referenzen'
      ],
    ];
  }
}