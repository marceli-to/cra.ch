<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Http\Resources\DataCollection;
use App\Http\Requests\ResumeStoreRequest;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Resume::orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Resume $resume
   * @return \Illuminate\Http\Response
   */
  public function find(Resume $resume)
  {
    return response()->json(['resume' => Resume::find($resume->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ResumeStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ResumeStoreRequest $request)
  {
    $resume = Resume::create([
      'periode' => $request->input('periode'),
      'description' => $request->input('description'),
    ]);
    $this->handleFlag($resume, 'isPublish', $request->input('publish'));
    return response()->json(['resumeId' => $resume->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ResumeStoreRequest  $request
   * @param  Resume $resume
   * @return \Illuminate\Http\Response
   */
  public function update(ResumeStoreRequest $request, Resume $resume)
  {
    $resume = Resume::findOrFail($resume->id);
    $resume->periode = $request->input('periode');
    $resume->description = $request->input('description');
    $resume->save();
    $this->handleFlag($resume, 'isPublish', $request->input('publish'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given resume
   *
   * @param  Resume $resume
   * @return \Illuminate\Http\Response
   */
  public function toggle(Resume $resume)
  {
    if ($resume->hasFlag('isPublish'))
    {
      $resume->unflag('isPublish');
    }
    else
    {
      $resume->flag('isPublish');
    } 
    return response()->json($resume->hasFlag('isPublish'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Resume $resume
   * @return \Illuminate\Http\Response
   */
  public function destroy(Resume $resume)
  {
    $resume->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Update the order the resumes
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

   public function order(Request $request)
   {
     $resumes = $request->get('resumes');
     foreach($resumes as $res)
     {
       $r = Resume::find($res['id']);
       $r->order = $res['order'];
       $r->save(); 
     }
     return response()->json('successfully updated');
   }

  /**
   * Handle flags of a resume
   *
   * @param Resume $resume
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Resume $resume, $flag, $value)
  {
    if ($value == 1)
    {
      $resume->flag($flag);
    }
    else
    {
      $resume->unflag($flag);
    }
    return $resume->hasFlag($flag);
  }
}