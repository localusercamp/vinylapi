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
    Schema::table('vinyl_records', function (Blueprint $table) {
      $table->string('hex')->fulltext()->nullable()->after('catalog_number');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('vinyl_records', function (Blueprint $table) {
      $table->dropColumn('hex');
    });
  }
};
