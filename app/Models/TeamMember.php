<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class TeamMember extends Model
{
  use HasFlags;

  protected $fillable = [
    'slug',
    'title',
  ];

  protected $appends = [
    'publish',
  ];

  public function resumes()
  {
    return $this->hasMany(Resume::class)->orderBy('order');
  }

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublish') ? 1 : 0;
  }
}
