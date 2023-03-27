<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
      'address' => 'required',
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
      'address.required' => [
        'field' => 'address',
        'error' => 'Adresse wird benötigt'
      ],
    ];
  }
}