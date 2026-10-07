<?php
// Same images, same sizes: jpg vs webp vs avif bytes for every image a page uses
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$slugs = array_slice($argv, 1);
$tot = ['jpg' => 0, 'webp' => 0, 'avif' => 0]; $n = 0;
foreach ($slugs as $slug) {
  $project = App\Models\Project::with('grids.gridItems.image')->where('slug', $slug)->first();
  foreach ($project->grids as $grid) foreach ($grid->gridItems as $item) {
    if (!$item->image) continue;
    foreach ([1200, 1600] as $size) {
      foreach (['jpg' => null, 'webp' => 'webp', 'avif' => 'avif'] as $k => $fm) {
        $url = 'http://127.0.0.1:8793' . $item->image->url($size, $fm);
        $tot[$k] += (int) shell_exec('curl -s -o /dev/null -w "%{size_download}" ' . escapeshellarg($url));
      }
      $n++;
    }
  }
}
printf("%d renditions: jpg/png %.1f MB, webp %.1f MB (%d%%), avif %.1f MB (%d%%)\n", $n, $tot['jpg']/1e6, $tot['webp']/1e6, 100*$tot['webp']/$tot['jpg'], $tot['avif']/1e6, 100*$tot['avif']/$tot['jpg']);
