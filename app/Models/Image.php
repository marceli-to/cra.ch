<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Base
{
  use HasFactory, SoftDeletes;

  protected $casts = [
    'created_at' => "datetime:d.m.Y",
  ];

	protected $fillable = [
    'uuid',
    'name',
    'original_name',
    'extension',
    'size',
    'caption',
    'description',
    'orientation',
		'coords_w',
    'coords_h',
    'coords_x',
    'coords_y',
    'order',
    'publish',
    'locked',
    'imageable_id',
    'imageable_type'
  ];

  public function imageable()
  {
    return $this->morphTo();
  }

	/**
   * Scope for published images
   */

	public function scopePublish($query)
	{
		return $query->where('publish', 1);
	}

	/**
   * Scope for locked images
   */

	public function scopeLocked($query)
	{
		return $query->where('locked', 1);
	}

	/**
	 * Get the cropping coordinates
	 *
	 * @return string
	 */

	public function getCoordsAttribute()
	{
    $coords = '0,0,0,0';
    if ($this->coords_w && $this->coords_h)
    {
      $coords = floor($this->coords_w) . ',' .  floor($this->coords_h) . ',' .  floor($this->coords_x) . ',' .  floor($this->coords_y);
    }
    return $coords;
  }

	/**
	 * Get the image ratio
	 *
	 * @return string
	 */
  
	public function getRatioAttribute()
	{
    if (($this->coords_w && $this->coords_h) && ($this->coords_w > $this->coords_h))
    {
      return 'wide';
    }
    return '';
  }
}
