<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Resume extends Model
{
  use HasFlags;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
  protected $fillable = [
    'periode',
    'description'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

   protected $appends = [
    'publish',
  ];

  /**
   * Get the publish attribute
   * 
   */

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublish') ? 1 : 0;    
  } 
}
