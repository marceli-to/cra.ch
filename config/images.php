<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Stored originals
  |--------------------------------------------------------------------------
  |
  | Longest side of a stored original, in pixels. Larger uploads are scaled
  | down on upload; `php artisan images:resize` does the same for existing
  | files. The largest rendition served is 2600 px (the old /img/crop sizes),
  | so this leaves room for crops of a part of an image.
  |
  */

  'max_edge' => (int) env('IMAGES_MAX_EDGE', 6000),

  'jpeg_quality' => 90,

  // Untouched copies of resized originals, outside the public disk
  'backup_path' => storage_path('app/originals'),

  /*
  |--------------------------------------------------------------------------
  | Uploads
  |--------------------------------------------------------------------------
  */

  'upload' => [
    'extensions' => ['jpg', 'jpeg', 'png'],

    // Kilobytes. PHP's upload_max_filesize and post_max_size must allow it.
    'max_kb' => 30 * 1024,

    // Decoding needs ~5 bytes per pixel with GD; this keeps a single upload
    // within reach of a normal memory_limit.
    'max_megapixels' => 60,
  ],

];
