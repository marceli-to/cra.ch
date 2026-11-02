<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * What a teaser's link leads to, for screen readers and search engines only
 * (the link itself still reads "Mehr")
 */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('articles', function (Blueprint $table) {
      $table->string('linkDescription')->after('linkText')->nullable();
    });
  }

  public function down(): void
  {
    Schema::table('articles', function (Blueprint $table) {
      $table->dropColumn('linkDescription');
    });
  }
};
