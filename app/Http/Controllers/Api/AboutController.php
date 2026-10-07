<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Http\Resources\DataCollection;
use App\Models\About;

class AboutController extends Controller
{
  public function get()
  {
    return new DataCollection(About::with('images', 'flags')->get());
  }

  /**
   * All about images (grid image picker)
   */
  public function getImages()
  {
    return response()->json(['data' => About::with('images')->get()->flatMap->images->values()]);
  }

  public function find(About $about)
  {
    return response()->json(['about' => $about->load('images')]);
  }

  public function store(AboutRequest $request)
  {
    $about = (new SaveAction)->execute(new About, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json(['aboutId' => $about->id]);
  }

  public function update(AboutRequest $request, About $about)
  {
    (new SaveAction)->execute($about, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json('successfully updated');
  }

  public function toggle(About $about)
  {
    return response()->json((new ToggleFlagAction)->execute($about));
  }

  public function destroy(About $about)
  {
    (new DeleteAction)->execute($about);
    return response()->json('successfully deleted');
  }
}
