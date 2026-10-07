<?php
namespace App\Models;
use App\Models\Concerns\HasGrids;
use Illuminate\Database\Eloquent\Model;

/**
 * The home page: a single row (id 1) holding the grid
 */
class Home extends Model
{
  use HasGrids;

  protected $table = 'home';
}
