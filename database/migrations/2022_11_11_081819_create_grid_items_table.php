<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
  Schema::create('grid_items', function (Blueprint $table) {
    $table->id();
    $table->tinyInteger('position')->default(0);
    $table->foreignId('project_id')->nullable()->constrained();
    $table->foreignId('diary_id')->nullable()->constrained();
    $table->foreignId('image_id')->nullable()->constrained();
    $table->foreignId('article_id')->nullable()->constrained();
    $table->foreignId('grid_id')->constrained();
    $table->timestamps();
  });
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
  Schema::dropIfExists('grid_items');
}
};
