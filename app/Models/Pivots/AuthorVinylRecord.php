<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuthorVinylRecord extends Pivot
{
  public $timestamps = false;

  protected $fillable = [
    'author_id',
    'vinyl_record_id',
    'is_main',
  ];
}
