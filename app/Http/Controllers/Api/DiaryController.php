<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiaryRequest;
use App\Http\Resources\DataCollection;
use App\Models\Diary;
use App\Models\GridItem;

class DiaryController extends Controller
{
  public function get()
  {
    return new DataCollection(Diary::with('flags')->get());
  }

  public function find(Diary $diary)
  {
    $diary->load('images', 'grids.gridItems.image');
    GridItem::loadLinkedProjects($diary->grids->flatMap->gridItems);

    return response()->json(['diary' => $diary]);
  }

  public function store(DiaryRequest $request)
  {
    $diary = (new SaveAction)->execute(new Diary, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json(['diaryId' => $diary->id]);
  }

  public function update(DiaryRequest $request, Diary $diary)
  {
    (new SaveAction)->execute($diary, $request->fields(), ['isPublish' => $request->boolean('publish')], $request->input('images', []));
    return response()->json('successfully updated');
  }

  public function toggle(Diary $diary)
  {
    return response()->json((new ToggleFlagAction)->execute($diary));
  }

  public function destroy(Diary $diary)
  {
    (new DeleteAction)->execute($diary);
    return response()->json('successfully deleted');
  }
}
