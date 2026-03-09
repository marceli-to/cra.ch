<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Http\Resources\DataCollection;
use App\Http\Requests\TeamMemberStoreRequest;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
  public function get()
  {
    return new DataCollection(TeamMember::orderBy('id')->get());
  }

  public function store(TeamMemberStoreRequest $request)
  {
    $teamMember = TeamMember::create([
      'slug' => $request->input('slug'),
      'title' => $request->input('title'),
    ]);
    $this->handleFlag($teamMember, 'isPublish', $request->input('publish'));
    return response()->json($teamMember);
  }

  public function find(TeamMember $teamMember)
  {
    return response()->json(['teamMember' => TeamMember::find($teamMember->id)]);
  }

  public function update(TeamMemberStoreRequest $request, TeamMember $teamMember)
  {
    $teamMember = TeamMember::findOrFail($teamMember->id);
    $teamMember->title = $request->input('title');
    $teamMember->save();
    $this->handleFlag($teamMember, 'isPublish', $request->input('publish'));
    return response()->json('successfully updated');
  }

  public function toggle(TeamMember $teamMember)
  {
    if ($teamMember->hasFlag('isPublish'))
    {
      $teamMember->unflag('isPublish');
    }
    else
    {
      $teamMember->flag('isPublish');
    }
    return response()->json($teamMember->hasFlag('isPublish'));
  }

  protected function handleFlag(TeamMember $teamMember, $flag, $value)
  {
    if ($value == 1)
    {
      $teamMember->flag($flag);
    }
    else
    {
      $teamMember->unflag($flag);
    }
    return $teamMember->hasFlag($flag);
  }
}
