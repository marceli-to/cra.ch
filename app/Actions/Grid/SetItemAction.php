<?php
namespace App\Actions\Grid;
use App\Models\GridItem;

/**
 * Fill a slot: an image (optionally linking to a project or page), or an
 * article. Whatever the slot showed before is replaced.
 */
class SetItemAction
{
  public function execute(GridItem $item, array $data): GridItem
  {
    $item->fill([
      'position' => $data['position'],
      'image_id' => $data['image_id'] ?? null,
      'project_id' => $data['project_id'] ?? null,
      'diary_id' => $data['diary_id'] ?? null,
      'article_id' => $data['article_id'] ?? null,
      'page' => $data['page'] ?? null,
    ])->save();

    return $item;
  }
}
