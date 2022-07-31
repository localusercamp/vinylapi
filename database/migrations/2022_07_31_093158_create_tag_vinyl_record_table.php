<?php

use App\Models\Tag;
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
    Schema::create('tag_vinyl_record', function (Blueprint $table) {
      $table->id();

      $table->foreignIdFor(Tag::class);
      $table->foreignIdFor(VinylRecord::class);

      $table->foreign('tag_id')->references('id')->on('tags');
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
    Schema::dropIfExists('tag_vinyl_record');
  }
};
