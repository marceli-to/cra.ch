<?php
// Render production URLs through the new ImageController from the untouched
// snapshot and compare with what image-cache produced on production.
[$_, $app, $snapshot, $matched] = $argv;
require $app . '/vendor/autoload.php';
$laravel = require $app . '/bootstrap/app.php';
$laravel->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

class SnapshotImageController extends App\Http\Controllers\ImageController
{
  public function __construct(public string $snapshot)
  {
    $this->server = League\Glide\ServerFactory::create([
      'source' => $snapshot, 'cache' => '/tmp/crverify/cache', 'driver' => App\Support\Glide::driver(),
    ]);
  }
  protected function source(string $filename): string { return $this->snapshot . '/uploads/' . $filename; }
}

$controller = new SnapshotImageController($snapshot);
$jobs = json_decode(file_get_contents($matched), true);
foreach (glob("$snapshot/cache/thumbnail/*") as $p) $jobs[] = [$p, basename($p), 'thumbnail', null, null];

$stats = ['same_size' => 0, 'diff_size' => 0, 'errors' => 0];
$worst = [];
foreach ($jobs as [$cached, $name, $size, $coords, $ratio]) {
  try {
    $response = $size === 'thumbnail'
      ? $controller->thumbnail($name)
      : $controller->crop($name, $size === null ? null : (string) $size, $coords, $ratio);
    $new = new Imagick(); $new->readImageBlob($response->getContent());
    $old = new Imagick($cached);
    if ([$new->getImageWidth(), $new->getImageHeight()] !== [$old->getImageWidth(), $old->getImageHeight()]) {
      $stats['diff_size']++;
      echo "SIZE $name $size $coords: old {$old->getImageWidth()}x{$old->getImageHeight()} new {$new->getImageWidth()}x{$new->getImageHeight()}\n";
      continue;
    }
    $stats['same_size']++;
    $old->transformImageColorspace(Imagick::COLORSPACE_SRGB); $new->transformImageColorspace(Imagick::COLORSPACE_SRGB);
    [, $rmse] = $old->compareImages($new, Imagick::METRIC_ROOTMEANSQUAREDERROR);
    $worst[] = [$rmse, "$name $size $coords $ratio"];
  } catch (Throwable $e) {
    $stats['errors']++;
    echo "ERROR $name $size $coords: " . get_class($e) . ' ' . $e->getMessage() . "\n";
  }
}
usort($worst, fn ($a, $b) => $b[0] <=> $a[0]);
$values = array_column($worst, 0); sort($values);
echo json_encode($stats), "\n";
printf("RMSE median %.4f, p95 %.4f, max %.4f\n", $values[intdiv(count($values), 2)], $values[(int) (count($values) * 0.95)], end($values));
foreach (array_slice($worst, 0, 5) as [$r, $what]) printf("  %.4f %s\n", $r, $what);
