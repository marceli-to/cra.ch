<?php
namespace App\Models;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
  use HasImages, HasPublishFlag;

  protected $fillable = [
    'column_one',
    'column_two',
  ];

  protected $appends = [
    'publish',
  ];
}
