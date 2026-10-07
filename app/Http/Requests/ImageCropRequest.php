<?php
namespace App\Http\Requests;

class ImageCropRequest extends AdminRequest
{
  public function rules(): array
  {
    return [
      'coords_w' => 'required|numeric|min:0',
      'coords_h' => 'required|numeric|min:0',
      'coords_x' => 'required|numeric|min:0',
      'coords_y' => 'required|numeric|min:0',
    ];
  }
}
