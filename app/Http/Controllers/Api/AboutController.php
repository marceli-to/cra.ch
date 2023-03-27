<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\AboutStoreRequest;
use Illuminate\Http\Request;

class AboutController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(About::with('images')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  About $about
   * @return \Illuminate\Http\Response
   */
  public function find(About $about)
  {
    return response()->json(['about' => About::with('images')->find($about->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\AboutStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(AboutStoreRequest $request)
  {
    $about = About::create([
      'description' => $request->input('description'),
      'cooperation' => $request->input('cooperation'),
      'membership' => $request->input('membership'),
    ]);
    $this->handleFlag($about, 'isPublish', $request->input('publish'));
    $this->handleImages($about, $request->input('images'));
    return response()->json(['aboutId' => $about->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\AboutStoreRequest  $request
   * @param  About $about
   * @return \Illuminate\Http\Response
   */
  public function update(AboutStoreRequest $request, About $about)
  {
    $about = About::findOrFail($about->id);
    $about->description = $request->input('description');
    $about->cooperation = $request->input('cooperation');
    $about->membership = $request->input('membership');
    $about->save();
    $this->handleFlag($about, 'isPublish', $request->input('publish'));
    $this->handleImages($about, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given about
   *
   * @param  About $about
   * @return \Illuminate\Http\Response
   */
  public function toggle(About $about)
  {
    if ($about->hasFlag('isPublish'))
    {
      $about->unflag('isPublish');
    }
    else
    {
      $about->flag('isPublish');
    } 
    return response()->json($about->hasFlag('isPublish'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  About $about
   * @return \Illuminate\Http\Response
   */
  public function destroy(About $about)
  {
    $about->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle flags of a about
   *
   * @param About $about
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(About $about, $flag, $value)
  {
    if ($value == 1)
    {
      $about->flag($flag);
    }
    else
    {
      $about->unflag($flag);
    }
    return $about->hasFlag($flag);
  }
  /**
   * Handle associated images
   *
   * @param About $about
   * @param Array $images
   * @return void
   */  

  protected function handleImages(About $about, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $about->id;
      $i->imageable_type = About::class;
      $i->save();
    }
  }
}