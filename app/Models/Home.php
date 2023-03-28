<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
  protected $table = 'home';

  /**
   * The images that belong to this model.
   */

  public function image()
  {
    return $this->morphOne(Image::class, 'imageable');
  }

  public function publishedImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1);
  }

  public function grids()
  {
    return $this->morphMany(Grid::class, 'gridable')->orderBy('order');
  }
}
