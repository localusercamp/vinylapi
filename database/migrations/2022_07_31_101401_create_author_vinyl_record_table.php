<?php

use App\Models\Author;
use App\Models\VinylRecord;
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
    Schema::create('author_vinyl_record', function (Blueprint $table) {
      $table->id();

      $table->foreignIdFor(Author::class);
      $table->foreignIdFor(VinylRecord::class);

      $table->foreign('author_id')->references('id')->on('authors');
      $table->foreign('vinyl_record_id')->references('id')->on('vinyl_records');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('author_vinyl_record');
  }
};
