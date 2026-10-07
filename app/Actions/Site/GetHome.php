<?php
namespace App\Actions\Site;
use App\Models\Home;

class GetHome
{
  public function execute(): array
  {
    return [
      'grid' => Home::with('grids.gridItems.image', 'grids.gridItems.project', 'grids.gridItems.article')->find(1),
    ];
  }
}
