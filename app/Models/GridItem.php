<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class GridItem extends Base
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
   * The discourse article that belongs to this image grid item.
   */
  
  public function discourse()
  {
    return $this->belongsTo(Discourse::class);
  }

  /**
   * The project that belongs to this image grid item.
   */
  
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

}
