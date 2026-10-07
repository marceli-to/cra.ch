<?php
namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * A form of the admin. Access is checked by the route (auth, role:admin).
 * Errors go out as `errors: {field: [{field, error}]}`: the admin marks
 * the fields by `field` (mixins/ErrorHandling.js).
 */
abstract class AdminRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  /**
   * `publish` and `images` are handled apart from the model's fields
   */
  public function fields(): array
  {
    return collect($this->validated())->except(['publish', 'images'])->all();
  }

  protected function failedValidation(Validator $validator)
  {
    $errors = collect($validator->errors()->messages())
      ->map(fn (array $messages, string $field) => array_map(fn ($message) => ['field' => $field, 'error' => $message], $messages));

    throw new HttpResponseException(response()->json([
      'message' => $validator->errors()->first(),
      'errors' => $errors,
    ], 422));
  }

  /**
   * Rules shared by forms with a publish switch and images
   */
  protected function publishAndImages(): array
  {
    return [
      'publish' => 'nullable',
      'images' => 'nullable|array',
      'images.*.id' => 'required|integer|exists:images,id',
    ];
  }
}
