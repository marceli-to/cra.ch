<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
          $table->id();
          $table->string('uuid', 36);
          $table->string('name', 255);
          $table->string('original_name', 255);
          $table->string('extension', 4);
          $table->float('size', 24, 0)->default(0);
          $table->string('caption')->nullable();
          $table->text('description')->nullable();
          $table->tinyInteger('order')->default(-1);
          $table->tinyInteger('publish')->default(0);
          $table->tinyInteger('locked')->default(0);
          $table->nullableMorphs('fileable');
          $table->softDeletes();
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
        Schema::dropIfExists('files');
    }
}
