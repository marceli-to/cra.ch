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
    Schema::create('projects', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique();
      $table->text('text')->nullable();
      $table->text('text_services')->nullable();
      $table->text('text_info')->nullable();
      $table->string('type')->nullable();
      $table->string('location')->nullable();
      $table->string('periode')->nullable();
      $table->unsignedBigInteger('state_id')->nullable();
      $table->foreign('state_id')->references('id')->on('states');
      $table->tinyInteger('order')->default(-1);
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
    Schema::dropIfExists('projects');
  }
};
