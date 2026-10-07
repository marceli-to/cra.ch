<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Http\Resources\DataCollection;
use App\Models\TeamMember;

class TeamMemberController extends Controller
{
  public function get()
  {
    return new DataCollection(TeamMember::with('flags')->orderBy('id')->get());
  }

  public function find(TeamMember $teamMember)
  {
    return response()->json(['teamMember' => $teamMember]);
  }

  public function store(TeamMemberRequest $request)
  {
    $teamMember = (new SaveAction)->execute(new TeamMember, $request->fields(), ['isPublish' => $request->boolean('publish')]);
    return response()->json($teamMember);
  }

  public function update(TeamMemberRequest $request, TeamMember $teamMember)
  {
    (new SaveAction)->execute($teamMember, $request->fields(), ['isPublish' => $request->boolean('publish')]);
    return response()->json('successfully updated');
  }

  public function toggle(TeamMember $teamMember)
  {
    return response()->json((new ToggleFlagAction)->execute($teamMember));
  }
}
