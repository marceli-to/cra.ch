<?php
namespace App\Http\Controllers\Api;
use App\Models\File;
use App\Services\Media;
use App\Http\Resources\DataCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
  /**
   * Get a list of files
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(File::orderBy('created_at')->get());
  }

  /**
   * Get a single file for a given file
   * 
   * @param File $file
   * @return \Illuminate\Http\Response
   */
  public function find(File $file)
  {
    $file = File::findOrFail($file->id);
    return response()->json($file);
  }

  /**
   * Store a newly added file
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
    $data['fileable_id']   = $request->input('fileable_id') ? $request->input('fileable_id') : NULL;
    $data['fileable_type'] = $request->input('fileable_type') ? "App\Models\\" . $request->input('fileable_type') : NULL;

    // Create file
    $file = File::create($data);
    $file->save();
    return response()->json(['fileId' => $file->id]);
  }

  /**
   * Update a file for a given file
   *
   * @param File $file
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function update(File $file, Request $request)
  {
    $file = File::findOrFail($file->id);
    $file->update($request->all());
    return response()->json('successfully updated');
  }

  /**
   * Update the order of the given files
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $files = $request->get('files');

    foreach($files as $file)
    {
      $i = File::find($file['id']);
      $i->order = $file['order'];
      $i->save(); 
    }
    
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given file
   *
   * @param  File $file
   * @return \Illuminate\Http\Response
   */
  public function toggle(File $file)
  {
    $file->publish = $file->publish == 0 ? 1 : 0;
    $file->save();
    return response()->json($file->publish);
  }

  /**
   * Remove the specified file from storage
   *
   * @param  string $file
   * @return \Illuminate\Http\Response
   */
  
  public function destroy($file)
  {
    // Delete from database
    $record = File::where('name', '=', $file)->first();
    
    if ($record)
    {
      $record->delete();
    }

    // Delete from storage
    $directories = Storage::allDirectories('public');
    foreach($directories as $d)
    {
      Storage::delete($d . '/'. $file);
    }
    
    return response()->json('successfully deleted');
  }

  /**
   * Upload an file
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
   * Delete a file
   * 
   * @param  String $file
   * @return \Illuminate\Http\Response
   */

  public function delete($file)
  { 
    $media = (new Media())->remove($file, TRUE);
    return response()->json($media);
  }
}
