<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\ServiceStoreRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Service::with('images')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Service $service
   * @return \Illuminate\Http\Response
   */
  public function find(Service $service)
  {
    return response()->json(['service' => Service::with('images')->find($service->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ServiceStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ServiceStoreRequest $request)
  {
    $service = Service::create([
      'column_one' => $request->input('column_one'),
      'column_two' => $request->input('column_two'),
    ]);
    $this->handleFlag($service, 'isPublish', $request->input('publish'));
    $this->handleImages($service, $request->input('images'));
    return response()->json(['serviceId' => $service->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ServiceStoreRequest  $request
   * @param  Service $service
   * @return \Illuminate\Http\Response
   */
  public function update(ServiceStoreRequest $request, Service $service)
  {
    $service = Service::findOrFail($service->id);
    $service->column_one = $request->input('column_one');
    $service->column_two = $request->input('column_two');
    $service->save();
    $this->handleFlag($service, 'isPublish', $request->input('publish'));
    $this->handleImages($service, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given service
   *
   * @param  Service $service
   * @return \Illuminate\Http\Response
   */
  public function toggle(Service $service)
  {
    if ($service->hasFlag('isPublish'))
    {
      $service->unflag('isPublish');
    }
    else
    {
      $service->flag('isPublish');
    } 
    return response()->json($service->hasFlag('isPublish'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Service $service
   * @return \Illuminate\Http\Response
   */
  public function destroy(Service $service)
  {
    $service->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle flags of a service
   *
   * @param Service $service
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Service $service, $flag, $value)
  {
    if ($value == 1)
    {
      $service->flag($flag);
    }
    else
    {
      $service->unflag($flag);
    }
    return $service->hasFlag($flag);
  }

  /**
   * Handle associated images
   *
   * @param Service $service
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Service $service, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $service->id;
      $i->imageable_type = Service::class;
      $i->save();
    }
  }
}
