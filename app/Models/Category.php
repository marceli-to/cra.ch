<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
  protected $fillable = [
    'title',
    'slug',
    'order',
  ];

  public function projects(): BelongsToMany
  {
    return $this->belongsToMany(Project::class);
  }
}
