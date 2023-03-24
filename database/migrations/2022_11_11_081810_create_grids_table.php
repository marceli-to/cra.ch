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
    Schema::create('grids', function (Blueprint $table) {
      $table->id();
      $table->string('layout', 5);
      $table->tinyInteger('order')->default(-1);
      $table->nullableMorphs('gridable');
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
    Schema::dropIfExists('grids');
  }
};
