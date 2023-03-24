<?php
namespace App\Services;
use App\Models\GridItem;
use Illuminate\Http\Request;

class GridItems
{ 
  /**
   * Get grid items by type (event, teaser)
   * 
   * @param String $type
   */
  
  public function get($type = NULL)
  {
    if ($type == 'event')
    {
      return GridItem::with('event.image')->orderBy('order')->whereNotNull('event_id')->get();
    }
    if ($type == 'teaser')
    {
      return GridItem::with('teaser.image', 'teaser.link')->orderBy('order')->whereNotNull('teaser_id')->get();
    }
  }
}
