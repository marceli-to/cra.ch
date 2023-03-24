<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Home extends Base
{

  protected $table = 'home';

  public function grids()
  {
    return $this->morphMany(Grid::class, 'gridable')->orderBy('order');
  }

}
