<?php
namespace App\Models;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
  use HasPublishFlag;

  protected $fillable = [
    'team_member_id',
    'periode',
    'description',
  ];

  protected $appends = [
    'publish',
  ];

  public function teamMember(): BelongsTo
  {
    return $this->belongsTo(TeamMember::class);
  }
}
