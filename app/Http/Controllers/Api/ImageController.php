<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\ReorderAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleAttributeAction;
use App\Actions\Image\CropAction;
use App\Actions\Image\DeleteAction;
use App\Actions\Image\StoreAction;
use App\Actions\Image\UploadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageCropRequest;
use App\Http\Requests\ImageStoreRequest;
use App\Http\Requests\ImageUpdateRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\DataCollection;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
  public function get()
  {
    return new DataCollection(Image::orderBy('created_at')->get());
  }

  public function find(Image $image)
  {
    return response()->json($image);
  }

  /**
   * Step 1: the file (Dropzone)
   */
  public function upload(ImageUploadRequest $request)
  {
    return response()->json((new UploadAction)->execute($request->file('file')));
  }

  /**
   * Step 2: its record
   */
  public function store(ImageStoreRequest $request)
  {
    $image = (new StoreAction)->execute($request->validated());
    return response()->json(['imageId' => $image->id]);
  }

  public function update(ImageUpdateRequest $request, Image $image)
  {
    (new SaveAction)->execute($image, $request->validated());
    return response()->json('successfully updated');
  }

  public function order(Request $request)
  {
    (new ReorderAction)->execute(Image::class, $this->orderItems($request, 'images'));
    return response()->json('successfully updated');
  }

  public function toggle(Image $image)
  {
    return response()->json((new ToggleAttributeAction)->execute($image, 'publish'));
  }

  /**
   * The project's preview image (work list)
   */
  public function preview(Image $image)
  {
    return response()->json((new ToggleAttributeAction)->execute($image, 'preview'));
  }

  public function coords(ImageCropRequest $request, Image $image)
  {
    (new CropAction)->execute($image, $request->validated());
    return response()->json('successfully updated');
  }

  /**
   * By file name, not id
   */
  public function destroy(string $image)
  {
    abort_unless(preg_match('/^[^.\/\\\\][^\/\\\\]*$/', $image), 404);

    (new DeleteAction)->execute($image);
    return response()->json('successfully deleted');
  }
}
