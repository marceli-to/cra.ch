<?php
namespace App\Http\Requests;
use App\Services\ImageResizer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ImageUploadRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    $extensions = implode(',', config('images.upload.extensions'));

    return [
      'file' => [
        // Stop at the first failure: never try to read a file that failed the checks before
        'bail',
        'required',
        'file',
        // Both: the name must say jpg/png, and so must the content
        "extensions:{$extensions}",
        "mimes:{$extensions}",
        'max:' . config('images.upload.max_kb'),
        function ($attribute, $file, $fail) {
          $dimensions = app(ImageResizer::class)->dimensions($file->getPathname());
          if (!$dimensions)
          {
            return $fail('Das Bild kann nicht gelesen werden.');
          }
          $megapixels = $dimensions[0] * $dimensions[1] / 1000000;
          $max = config('images.upload.max_megapixels');
          if ($megapixels > $max)
          {
            $fail(sprintf('Das Bild hat %d×%d Pixel (%.0f MP), erlaubt sind max. %d MP.', $dimensions[0], $dimensions[1], $megapixels, $max));
          }
        },
      ],
    ];
  }

  public function messages()
  {
    $mb = round(config('images.upload.max_kb') / 1024);

    return [
      'file.required' => 'Es wurde keine Datei hochgeladen.',
      'file.file' => 'Der Upload ist fehlgeschlagen.',
      'file.uploaded' => "Der Upload ist fehlgeschlagen. Ist die Datei grösser als {$mb} MB?",
      'file.extensions' => 'Erlaubt sind nur JPG- und PNG-Dateien.',
      'file.mimes' => 'Der Inhalt der Datei ist kein JPG oder PNG.',
      'file.max' => "Die Datei ist grösser als {$mb} MB.",
    ];
  }

  /**
   * Dropzone shows `error`; the rest is the usual validation payload
   */
  protected function failedValidation(Validator $validator)
  {
    $message = $validator->errors()->first('file');

    throw new HttpResponseException(response()->json([
      'error' => $message,
      'message' => $message,
      'errors' => $validator->errors(),
    ], 422));
  }
}
