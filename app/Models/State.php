<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A project's state ("in Bearbeitung", "realisiert", ...)
 */
class State extends Model
{
  protected $fillable = [
    'title',
  ];

  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }
}
