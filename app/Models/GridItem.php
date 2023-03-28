<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GridItem extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'position',
    'project_id',
    'image_id',
    'grid_id'
  ];


  /**
   * The grid that belongs to this image grid item.
   */
  
  public function grid()
  {
    return $this->belongsTo(Grid::class);
  }

  /**
   * The image that belongs to this image grid item.
   */
  
  public function image()
  {
    return $this->belongsTo(Image::class);
  }


  /**
   * The project that belongs to this image grid item.
   */
  
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

}
