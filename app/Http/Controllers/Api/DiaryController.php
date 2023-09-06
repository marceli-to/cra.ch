<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataCollection;
use App\Models\Diary;
use App\Models\Image;
use App\Http\Requests\DiaryStoreRequest;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
  
  /**
   * Get a list of diaries
   * 
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Diary::get());
  }

  /**
   * Get a single diary
   * 
   * @param Diary $diary
   * @return \Illuminate\Http\Response
   */
  public function find(Diary $diary)
  {
    $diary = Diary::with('images', 'grids.gridItems.image')->find($diary->id);
    return response()->json(['diary' => $diary]);
  }

  /**
   * Store a newly created diary
   *
   * @param  \Illuminate\Http\DiaryStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(DiaryStoreRequest $request)
  { 
    $diary = Diary::create([
      'description' => $request->input('description'),
    ]);
    $this->handleFlag($diary, 'isPublish', $request->input('publish'));
    $this->handleImages($diary, $request->input('images'));
    return response()->json(['diaryId' => $diary->id]);
  }

  /**
   * Update a diary for a given diary
   *
   * @param Diary $diary
   * @param  \Illuminate\Http\DiaryStoreRequest $request
   * @return \Illuminate\Http\Response
   */
  public function update(Diary $diary, DiaryStoreRequest $request)
  {
    $diary = Diary::findOrFail($diary->id);
    $diary->description = $request->input('description');
    $diary->save();
    $this->handleFlag($diary, 'isPublish', $request->input('publish'));
    $this->handleImages($diary, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given diary
   *
   * @param  Diary $diary
   * @return \Illuminate\Http\Response
   */
  public function toggle(Diary $diary)
  {
    if ($diary->hasFlag('isPublish'))
    {
      $diary->unflag('isPublish');
    }
    else
    {
      $diary->flag('isPublish');
    } 
    return response()->json($diary->hasFlag('isPublish'));
  }


  /**
   * Remove a diary
   *
   * @param  Diary $diary
   * @return \Illuminate\Http\Response
   */
  public function destroy(Diary $diary)
  {
    $diary->delete();
    return response()->json('successfully deleted');
  }


  /**
   * Handle associated images
   *
   * @param Diary $diary
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Diary $diary, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $diary->id;
      $i->imageable_type = Diary::class;
      $i->save();
    }
  }

  /**
   * Handle flags of a diary
   *
   * @param Diary $diary
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Diary $diary, $flag, $value)
  {
    if ($value == 1)
    {
      $diary->flag($flag);
    }
    else
    {
      $diary->unflag($flag);
    }
    return $diary->hasFlag($flag);
  }

}

