<?php
namespace App\Http\Controllers\Api;
use App\Actions\Content\DeleteAction;
use App\Actions\Content\ReorderAction;
use App\Actions\Content\SaveAction;
use App\Actions\Content\ToggleFlagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeRequest;
use App\Http\Resources\DataCollection;
use App\Models\Resume;
use App\Models\TeamMember;
use Illuminate\Http\Request;

/**
 * The CV entries of a team member
 */
class ResumeController extends Controller
{
  public function get()
  {
    return new DataCollection(Resume::with('flags')->orderBy('order')->get());
  }

  public function getByTeamMember(TeamMember $teamMember)
  {
    return new DataCollection($teamMember->resumes()->with('flags')->get());
  }

  public function find(Resume $resume)
  {
    return response()->json(['resume' => $resume]);
  }

  public function store(ResumeRequest $request)
  {
    $resume = (new SaveAction)->execute(new Resume, $request->fields(), ['isPublish' => $request->boolean('publish')]);
    return response()->json(['resumeId' => $resume->id]);
  }

  public function update(ResumeRequest $request, Resume $resume)
  {
    (new SaveAction)->execute($resume, $request->fields(), ['isPublish' => $request->boolean('publish')]);
    return response()->json('successfully updated');
  }

  public function toggle(Resume $resume)
  {
    return response()->json((new ToggleFlagAction)->execute($resume));
  }

  public function destroy(Resume $resume)
  {
    (new DeleteAction)->execute($resume);
    return response()->json('successfully deleted');
  }

  public function order(Request $request)
  {
    (new ReorderAction)->execute(Resume::class, $this->orderItems($request, 'resumes'));
    return response()->json('successfully updated');
  }
}
