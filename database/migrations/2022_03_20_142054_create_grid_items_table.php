<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGridItemsTable extends Migration
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
            // $table->foreignId('event_id')->nullable()->constrained();
            // $table->foreignId('teaser_id')->nullable()->constrained();
            $table->tinyInteger('order')->default(-1);
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
}
