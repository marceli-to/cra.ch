<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GridItem extends Base
{
  use HasFactory;

  protected $fillable = [
    'event_id',
    'teaser_id',
    'order'
  ];

  public function event()
  {
		$constraint = date('Y-m-d', time());
    return $this->hasOne(Event::class, 'id', 'event_id')->where('date', '>=', $constraint)->orWhere('sticky', 1)->orWhere('placeholder', 1);
  }

  public function teaser()
  {
    return $this->hasOne(Teaser::class, 'id', 'teaser_id');
  }
}
