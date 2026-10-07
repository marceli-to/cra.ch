<?php
namespace App\Actions\Site;
use App\Models\Diary;

class GetDiary
{
  public function execute(): array
  {
    return [
      'diary' => Diary::with('grids.gridItems.image')->flagged('isPublish')->first(),
    ];
  }
}
