<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
  protected $fillable = [
    'content',
  ];

  protected $casts = [
    'content' => 'array',
  ];
}
