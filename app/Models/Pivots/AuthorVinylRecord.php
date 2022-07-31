<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuthorVinylRecord extends Pivot
{
  protected $guarded = [
    'author_id',
    'vinyl_record_id',
  ];
}
