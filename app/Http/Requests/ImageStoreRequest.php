<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Storage;

/**
 * The record of an uploaded file, attached to a model or not
 */
class ImageStoreRequest extends AdminRequest
{
  /**
   * Models images can belong to
   */
  public const IMAGEABLE_TYPES = ['About', 'Article', 'Contact', 'Diary', 'Home', 'Project', 'Service'];

  public function rules(): array
  {
    return [
      // A file that was uploaded before, not a path
      'name' => ['required', 'string', 'regex:/^[^.\/\\\\][^\/\\\\]*$/', function ($attribute, $name, $fail) {
        if (!Storage::exists('public/uploads/' . $name))
        {
          $fail('Die Bilddatei existiert nicht.');
        }
      }],
      'original_name' => 'nullable|string|max:255',
      'extension' => 'nullable|string|max:4',
      'size' => 'nullable|numeric',
      'orientation' => 'nullable|string|max:25',
      'ratio' => 'nullable|string|max:255',
      'caption' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'coords_w' => 'nullable|numeric',
      'coords_h' => 'nullable|numeric',
      'coords_x' => 'nullable|numeric',
      'coords_y' => 'nullable|numeric',
      'preview' => 'nullable|boolean',
      'publish' => 'nullable|boolean',
      'imageable_type' => ['nullable', 'in:' . implode(',', self::IMAGEABLE_TYPES)],
      'imageable_id' => ['nullable', 'integer', 'required_with:imageable_type'],
    ];
  }
}
