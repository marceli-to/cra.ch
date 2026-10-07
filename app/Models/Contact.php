<?php
namespace App\Models;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  use HasImages, HasPublishFlag;

  protected $fillable = [
    'address',
    'description',
    'maps_uri',
    'imprint',
  ];

  protected $appends = [
    'publish',
  ];
}
