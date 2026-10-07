<?php
namespace App\Models;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
  use HasPublishFlag;

  protected $fillable = [
    'slug',
    'title',
  ];

  protected $appends = [
    'publish',
  ];

  public function resumes(): HasMany
  {
    return $this->hasMany(Resume::class)->orderBy('order');
  }
}
