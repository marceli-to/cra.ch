<?php
namespace App\Models;
use App\Models\Concerns\HasGrids;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
  use HasGrids, HasImages, HasPublishFlag;

  protected $fillable = [
    'description',
  ];

  protected $appends = [
    'publish',
    'articleContent',
  ];

  /**
   * The description as it appears in a grid slot
   */
  public function getArticleContentAttribute(): string
  {
    return '<article>' . $this->description . '</article>';
  }
}
