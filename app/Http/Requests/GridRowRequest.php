<?php
namespace App\Http\Requests;
use App\Models\Article;
use App\Models\Diary;
use App\Models\Home;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

/**
 * A new grid row: its layout, number of slots and the model it belongs to
 */
class GridRowRequest extends AdminRequest
{
  /**
   * Models with a grid (HasGrids)
   */
  public const OWNERS = ['Article' => Article::class, 'Diary' => Diary::class, 'Home' => Home::class, 'Project' => Project::class];

  public function rules(): array
  {
    return [
      // A template in components/galleries
      'layout' => ['required', 'string', 'max:20', 'regex:/^[0-9a-z_\-]+$/'],
      'items' => 'required|integer|min:1|max:6',
      'model.name' => 'required|in:' . implode(',', array_keys(self::OWNERS)),
      'model.id' => 'required|integer',
    ];
  }

  public function owner(): Model
  {
    return (self::OWNERS[$this->validated('model.name')])::findOrFail($this->validated('model.id'));
  }
}
