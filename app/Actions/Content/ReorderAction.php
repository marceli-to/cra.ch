<?php
namespace App\Actions\Content;
use Illuminate\Support\Facades\DB;

/**
 * Store the order of a list: `[['id' => 3, 'order' => 0], ...]`
 */
class ReorderAction
{
  /**
   * @param class-string<\Illuminate\Database\Eloquent\Model> $model
   */
  public function execute(string $model, array $items): void
  {
    DB::transaction(function () use ($model, $items) {
      foreach ($items as $item)
      {
        $model::whereKey($item['id'])->update(['order' => $item['order']]);
      }
    });
  }
}
