<?php

use App\Services\ImageResizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The upload's size after EXIF rotation, so the markup can clamp crops
 * without reading files. `ratio` can't be used: for EXIF-rotated photos it
 * holds the stored, unrotated size.
 */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('images', function (Blueprint $table) {
      $table->unsignedInteger('width')->nullable()->after('name');
      $table->unsignedInteger('height')->nullable()->after('width');
    });

    $resizer = new ImageResizer();

    DB::table('images')->select('id', 'name')->orderBy('id')->each(function ($row) use ($resizer) {
      if ($size = $resizer->dimensions(storage_path('app/public/uploads/' . $row->name)))
      {
        DB::table('images')->where('id', $row->id)->update(['width' => $size[0], 'height' => $size[1]]);
      }
    });
  }

  public function down(): void
  {
    Schema::table('images', function (Blueprint $table) {
      $table->dropColumn(['width', 'height']);
    });
  }
};
