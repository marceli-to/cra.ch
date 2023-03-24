<?php
namespace App\Http\Controllers\Api;
use App\Models\Image;
use App\Models\HeroImage;
use App\Services\Media;
use App\Http\Resources\DataCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
  /**
   * Get a list of images
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Image::orderBy('created_at')->get());
  }

  /**
   * Get a single image for a given image
   * 
   * @param Image $image
   * @return \Illuminate\Http\Response
   */
  public function find(Image $image)
  {
    $image = Image::findOrFail($image->id);
    return response()->json($image);
  }

  /**
   * Store a newly added image
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->all();

    // Generate UUID
    $data['uuid'] = \Str::uuid();

    // Add imagable id & type
    $data['imageable_id']   = $request->input('imageable_id') ? $request->input('imageable_id') : NULL;
    $data['imageable_type'] = $request->input('imageable_type') ? "App\Models\\" . $request->input('imageable_type') : NULL;

    // Create image
    $image = Image::create($data);
    $image->save();
    return response()->json(['imageId' => $image->id]);
  }

  /**
   * Update a image for a given image
   *
   * @param Image $image
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function update(Image $image, Request $request)
  {
    $image = Image::findOrFail($image->id);
    $image->update($request->all());
    return response()->json('successfully updated');
  }

  /**
   * Update the order of the given images
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $images = $request->get('images');

    foreach($images as $image)
    {
      $i = Image::find($image['id']);
      $i->order = $image['order'];
      $i->save(); 
    }
    
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given image
   *
   * @param  Image $image
   * @return \Illuminate\Http\Response
   */
  public function toggle(Image $image)
  {
    $image->publish = $image->publish == 0 ? 1 : 0;
    $image->save();
    return response()->json($image->publish);
  }

  /**
   * Update the cropping coords of the specified image
   *
   * @param Image $image
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function coords(Image $image, Request $request)
  {
    $image = Image::findOrFail($image->id);
    $image->coords_w = round($request->input('coords_w'), 12);
    $image->coords_h = round($request->input('coords_h'), 12);
    $image->coords_x = round($request->input('coords_x'), 12);
    $image->coords_y = round($request->input('coords_y'), 12);

    if ($image->coords_w > $image->coords_h)
    {
      $image->orientation = 'l';
    }
    else
    {
      $image->orientation = 'p';
    } 

    $image->save();
    $this->removeCachedImage($image);
    return response()->json('successfully updated');
  }

  /**
   * Remove the specified image from storage
   *
   * @param  string $image
   * @return \Illuminate\Http\Response
   */
  
  public function destroy($image)
  {
    // Delete from database
    $record = Image::where('name', '=', $image)->first();
    
    if ($record)
    {
      $record->delete();
    }

    // Delete from storage
    $directories = Storage::allDirectories('public');
    foreach($directories as $d)
    {
      Storage::delete($d . '/'. $image);
    }
    
    return response()->json('successfully deleted');
  }

  /**
   * Upload an image
   * 
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */

  public function upload(Request $request)
  { 
    $media = (new Media(['force_lowercase' => false]))->store($request);
    return response()->json($media);
  }

  /**
   * Delete an image
   * 
   * @param  String $image
   * @return \Illuminate\Http\Response
   */

  public function delete($image)
  { 
    $media = (new Media())->remove($image, TRUE);
    return response()->json($media);
  }

  /**
   * Remove cached version of the image
   *
   * @param Image $image
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  private function removeCachedImage(Image $image)
  {
    // Get an instance of the ImageCache class
    $cache = new \Intervention\Image\ImageCache();

    // Get a cached image from it and apply all of your templates / methods
    $image = $cache->make(storage_path('app/public/uploads/') . $image->name)->filter(new \App\Filters\Image\Template\Cache);

    // Remove the image from the cache by using its internal checksum
    Cache::forget($image->checksum());
  }
}
