<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\QueryBuilders\TagQueryBuilder;
use App\Collections\TagCollection;
use App\Models\Pivots\TagVinylRecord;

/**
 * @method static TagQueryBuilder query()
 */
class Tag extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = [
    'id',
  ];

  protected $hidden = [
    'pivot',
  ];

  public $timestamps = false;



  public function vinyl_records(): BelongsToMany
  {
    return $this->belongsToMany(VinylRecord::class)->using(TagVinylRecord::class);
  }



  public function newCollection(array $models = []): TagCollection
  {
    return new TagCollection($models);
  }

  public function newEloquentBuilder($query): TagQueryBuilder
  {
    return new TagQueryBuilder($query);
  }
}
