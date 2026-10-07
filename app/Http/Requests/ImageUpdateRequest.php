<?php
namespace App\Http\Requests;

/**
 * The edit dialog of an image; publish, preview and the crop have their
 * own endpoints
 */
class ImageUpdateRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'caption' => 'nullable|string|max:255',
      'description' => 'nullable|string',
    ];
  }
}
