<?php

use App\Enums\VinylRecordStatus;
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
    Schema::create('vinyl_records', function (Blueprint $table) {
      $table->id();
      $table->string('title', 128);
      $table->integer('price');
      $table->longText('description')->nullable();
      $table->string('status')->default(VinylRecordStatus::Draft->name);
      $table->string('condition')->nullable();
      $table->string('sleeve_condition')->nullable();
      $table->integer('release_year')->nullable();
      $table->string('catalog_number')->nullable();

      $table->unsignedBigInteger('country_id')->nullable();
      $table->unsignedBigInteger('genre_id')->nullable();
      $table->unsignedBigInteger('poster_id')->nullable();

      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('vinyl_records');
  }
};
