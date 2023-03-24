<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
      'title.de' => 'required',
      'category_ids' => 'required|array|min:1',
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
      'title.de.required' => [
        'field' => 'title',
        'error' => 'Titel'
      ],
      'category_ids.required' => [
        'field' => 'category_ids',
        'error' => 'Kategorie'
      ],
    ];
  }
}