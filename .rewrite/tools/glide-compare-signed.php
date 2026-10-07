<?php
// Same comparison as glide-compare.php, through the signed path: parameters
// as Image::url() builds them (crop clamped by Image::crop()).
[$_, $app, $snapshot, $matched] = $argv;
require $app . '/vendor/autoload.php';
$laravel = require $app . '/bootstrap/app.php';
$laravel->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

class SnapshotImageController extends App\Http\Controllers\ImageController
{
  public function __construct(public string $snapshot)
  {
    $this->server = League\Glide\ServerFactory::create(['source' => $snapshot, 'cache' => '/tmp/crverify/cache2', 'driver' => App\Support\Glide::driver()]);
  }
  public function renderPublic(string $f, array $p, string $fmt) { return $this->render($f, $p, $fmt); }
}
$controller = new SnapshotImageController($snapshot);
$resizer = new App\Services\ImageResizer();
$stats = ['same_size' => 0, 'diff_size' => 0, 'errors' => 0]; $values = [];
foreach (json_decode(file_get_contents($matched), true) as [$cached, $name, $size, $coords, $ratio]) {
  try {
    $image = new App\Models\Image(['name' => $name]);
    [$image->width, $image->height] = $resizer->dimensions("$snapshot/uploads/$name");
    if ($coords && $coords !== '0,0,0,0') [$image->coords_w, $image->coords_h, $image->coords_x, $image->coords_y] = explode(',', $coords);
    parse_str(parse_url($image->url($size ?? 2400), PHP_URL_QUERY), $params);
    unset($params['s']);
    $fmt = strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'png' ? 'png' : 'jpg';
    $new = new Imagick(); $new->readImageBlob($controller->renderPublic($name, $params, $fmt));
    $old = new Imagick($cached);
    if ([$new->getImageWidth(), $new->getImageHeight()] !== [$old->getImageWidth(), $old->getImageHeight()]) {
      $stats['diff_size']++; echo "SIZE $name $size $coords: old {$old->getImageWidth()}x{$old->getImageHeight()} new {$new->getImageWidth()}x{$new->getImageHeight()}\n"; continue;
    }
    $stats['same_size']++;
    $old->transformImageColorspace(Imagick::COLORSPACE_SRGB); $new->transformImageColorspace(Imagick::COLORSPACE_SRGB);
    $values[] = $old->compareImages($new, Imagick::METRIC_ROOTMEANSQUAREDERROR)[1];
  } catch (Throwable $e) { $stats['errors']++; echo "ERROR $name $size $coords: " . $e->getMessage() . "\n"; }
}
sort($values);
echo json_encode($stats), "\n";
printf("RMSE median %.4f, p95 %.4f, max %.4f\n", $values[intdiv(count($values), 2)], $values[(int) (count($values) * 0.95)], end($values));
