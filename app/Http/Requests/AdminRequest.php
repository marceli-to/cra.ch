<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A form of the admin. Access is checked by the route (auth, role:admin).
 * Errors go out in Laravel's format, `errors: {field: [message]}`; the admin
 * marks the fields by key (lib/http.js).
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
