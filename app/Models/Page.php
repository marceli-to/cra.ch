<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Base
{
  use HasFactory;

	protected $fillable = [
    'slug',
    'title',
    'subtitle',
    'text',
    'publish'
  ];

  public function articles()
  {
    return $this->hasMany(Article::class, 'page_id','id')->orderBy('order', 'ASC');
  }

  public function publishedArticles()
  {
    return $this->hasMany(Article::class, 'page_id','id')->orderBy('order', 'ASC')->where('publish', 1);
  }

  public function images()
  {
    return $this->morphMany(Image::class, 'imageable');
  }

  public function publishedImages()
  {
    return $this->morphMany(Image::class, 'imageable')->where('publish', 1);
  }

  public function publishedImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1);
  }
}
