<?php
namespace App\Console\Commands;
use App\Models\Image;
use App\Services\ImageResizer;
use App\Support\Glide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ResizeImages extends Command
{
  protected $signature = 'images:resize
                          {--max= : Longest side in pixels (default: config images.max_edge)}
                          {--file=* : Only these file names}
                          {--dry-run : List what would be resized, change nothing}
                          {--memory=2048M : memory_limit for this run (GD needs ~5 bytes per pixel)}';

  protected $description = 'Scale down stored originals that are larger than the configured size, keeping crops in place';

  public function handle(ImageResizer $resizer): int
  {
    ini_set('memory_limit', $this->option('memory'));

    $maxEdge = (int) ($this->option('max') ?: config('images.max_edge'));
    $uploads = storage_path('app/public/uploads');
    $backups = config('images.backup_path');
    $dryRun = $this->option('dry-run');

    if ($maxEdge < 1000)
    {
      $this->error("--max={$maxEdge} is too small; renditions go up to 2600 px.");
      return self::FAILURE;
    }

    $files = collect(File::files($uploads))
      ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ImageResizer::EXTENSIONS))
      ->when($this->option('file'), fn ($files, $only) => $files->filter(fn ($file) => in_array($file->getFilename(), $only)))
      ->filter(fn ($file) => $resizer->exceeds($file->getPathname(), $maxEdge))
      ->values();

    $this->info(sprintf(
      '%d of the originals in %s are larger than %d px (driver: %s)%s',
      $files->count(), $uploads, $maxEdge, $resizer->driverName(), $dryRun ? ' — dry run' : ''
    ));

    if ($files->isEmpty())
    {
      return self::SUCCESS;
    }

    $rows = [];
    $bytesBefore = 0;
    $bytesAfter = 0;
    $failed = 0;

    foreach ($files as $file)
    {
      $name = $file->getFilename();
      $path = $file->getPathname();
      $records = Image::withTrashed()->where('name', $name)->get();
      [$width, $height] = $resizer->dimensions($path);
      $sizeBefore = $file->getSize();
      $bytesBefore += $sizeBefore;

      if ($dryRun)
      {
        $rows[] = [$name, "{$width}×{$height}", $this->mb($sizeBefore), $this->usage($records)];
        continue;
      }

      try
      {
        // Keep the first untouched original; a later run with a smaller
        // --max must not overwrite it with an already resized file
        File::ensureDirectoryExists($backups);
        if (!File::exists("{$backups}/{$name}"))
        {
          File::copy($path, "{$backups}/{$name}");
        }

        [$oldWidth, $oldHeight, $newWidth, $newHeight] = $resizer->resize($path, $maxEdge);
        $size = File::size($path);
        $bytesAfter += $size;

        DB::transaction(function () use ($records, $oldWidth, $oldHeight, $newWidth, $newHeight, $size) {
          $fx = $newWidth / $oldWidth;
          $fy = $newHeight / $oldHeight;

          foreach ($records as $image)
          {
            if ($image->coords_w > 0 && $image->coords_h > 0)
            {
              $image->coords_x = round(min($image->coords_x * $fx, $newWidth), 12);
              $image->coords_y = round(min($image->coords_y * $fy, $newHeight), 12);
              $image->coords_w = round(min($image->coords_w * $fx, $newWidth - $image->coords_x), 12);
              $image->coords_h = round(min($image->coords_h * $fy, $newHeight - $image->coords_y), 12);
            }
            $image->size = $size;
            $image->ratio = "{$newWidth}x{$newHeight}";
            $image->width = $newWidth;
            $image->height = $newHeight;
            $image->timestamps = false;
            $image->save();
          }
        });

        Glide::forget($name);

        $rows[] = [$name, "{$oldWidth}×{$oldHeight} → {$newWidth}×{$newHeight}", $this->mb($sizeBefore) . ' → ' . $this->mb($size), $this->usage($records)];
      }
      catch (\Throwable $e)
      {
        $failed++;
        $rows[] = [$name, "{$width}×{$height}", 'FAILED', $e->getMessage()];
      }
    }

    $this->table(['File', 'Pixels', 'Size', 'Records'], $rows);

    if ($dryRun)
    {
      $this->info(sprintf('%s in %d files. Run without --dry-run to resize; untouched copies go to %s.', $this->mb($bytesBefore), $files->count(), $backups));
    }
    else
    {
      $this->info(sprintf('Resized %d files, %s of originals kept in %s.', $files->count() - $failed, $this->mb($bytesBefore), $backups));
    }

    return $failed ? self::FAILURE : self::SUCCESS;
  }

  private function usage($records): string
  {
    if ($records->isEmpty())
    {
      return 'none (orphan)';
    }

    $live = $records->whereNull('deleted_at')->count();
    $cropped = $records->filter(fn ($image) => $image->coords_w > 0)->count();

    return trim(sprintf('%d live, %d deleted%s', $live, $records->count() - $live, $cropped ? ", {$cropped} cropped" : ''));
  }

  private function mb(int $bytes): string
  {
    return number_format($bytes / 1048576, 1) . ' MB';
  }
}
