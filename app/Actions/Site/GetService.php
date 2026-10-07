<?php
namespace App\Actions\Site;
use App\Models\Service;

class GetService
{
  public function execute(): array
  {
    return [
      'service' => Service::with('publishedImages')->first(),
    ];
  }
}
