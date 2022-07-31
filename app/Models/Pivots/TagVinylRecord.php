<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TagVinylRecord extends Pivot
{
  protected $guarded = [
    'tag_id',
    'vinyl_record_id',
  ];
}
