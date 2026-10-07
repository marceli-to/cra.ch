<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\DataCollection;
use App\Models\Service;

class ServiceController extends Controller
{
  public function get()
  {
    return new DataCollection(Service::with('images', 'flags')->get());
  }

  /**
   * All service images (grid image picker)
   */
  public function getImages()
  {
    return response()->json(['data' => Service::with('images')->get()->flatMap->images->values()]);
  }

  public function find(Service $service)
  {
    return response()->json(['service' => $service->load('images')]);
  }

  public function store(ServiceRequest $request)
  {
    $service = (new SaveAction)->execute(new Service, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json(['serviceId' => $service->id]);
  }

  public function update(ServiceRequest $request, Service $service)
  {
    (new SaveAction)->execute($service, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json('successfully updated');
  }

  public function toggle(Service $service)
  {
    return response()->json((new ToggleFlagAction)->execute($service));
  }

  public function destroy(Service $service)
  {
    (new DeleteAction)->execute($service);
    return response()->json('successfully deleted');
  }
}
