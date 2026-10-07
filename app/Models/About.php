<?php
namespace App\Models;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasPublishFlag;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
  use HasImages, HasPublishFlag;

  protected $table = 'about';

  protected $fillable = [
    'description',
    'cooperation',
    'former_employees',
    'membership',
  ];

  protected $appends = [
    'publish',
  ];
}
