<?php
namespace App\Actions\Grid;
use App\Models\GridItem;

/**
 * Empty a slot; the slot itself stays
 */
class ResetItemAction
{
  public function execute(GridItem $item): void
  {
    $item->fill(['image_id' => null, 'project_id' => null, 'diary_id' => null, 'article_id' => null, 'page' => null])->save();
  }
}
