<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageResizer;
use App\Support\Glide;

class Image extends Model
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
    'ratio',
		'coords_w',
    'coords_h',
    'coords_x',
    'coords_y',
    'order',
    'preview',
    'publish',
    'locked',
    'imageable_id',
    'imageable_type'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'coords',
  ];

  /**
   * Relationships
   * 
   */

  public function imageable()
  {
    return $this->morphTo();
  }

	/**
   * Scope for preview images
   */

	public function scopePreview($query)
	{
		return $query->where('preview', 1);
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
   * Keep width/height (after EXIF rotation) in step with the file
   */
  protected static function booted(): void
  {
    static::saving(function (Image $image) {
      if ($image->name && ($image->isDirty('name') || !$image->width || !$image->height))
      {
        [$image->width, $image->height] = app(ImageResizer::class)->dimensions($image->path()) ?? [null, null];
      }
    });
  }

  public function path(): string
  {
    return storage_path('app/public/uploads/' . $this->name);
  }

  /**
   * [w, h, x, y] as ints, or null when the image is not cropped. A crop
   * reaching past the image is cut at its edges, as image-cache did.
   */
  public function crop(): ?array
  {
    if (!$this->coords_w || !$this->coords_h)
    {
      return null;
    }

    [$w, $h, $x, $y] = array_map(
      fn ($value) => max(0, (int) floor((float) $value)),
      [$this->coords_w, $this->coords_h, $this->coords_x, $this->coords_y]
    );

    if ($this->width && $this->height)
    {
      $w = max(1, min($w, $this->width - $x));
      $h = max(1, min($h, $this->height - $y));
    }

    return [$w, $h, $x, $y];
  }

  /**
   * Signed URL of the (cropped) image with its longer side at most $size
   */
  public function url(int $size, ?string $format = null): string
  {
    return Glide::url($this->name, $size, $this->crop(), $format);
  }
}
