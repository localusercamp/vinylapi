<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
  BelongsToMany,
  HasOne,
};

use App\QueryBuilders\VinylRecordQueryBuilder;
use App\Collections\VinylRecordCollection;
use App\Interfaces\Sellable;
use App\Models\Pivots\{
  TagVinylRecord,
  AuthorVinylRecord,
};

/**
 * @method static VinylRecordQueryBuilder query()
 */
class VinylRecord extends Model implements Sellable
{
  use HasFactory, SoftDeletes;

  protected $guarded = [
    'id',
    'cover_id',
  ];

  protected $hidden = [
    'pivot',
  ];



  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class)->using(TagVinylRecord::class);
  }

  public function authors(): BelongsToMany
  {
    return $this->belongsToMany(Author::class)->using(AuthorVinylRecord::class);
  }



  public function newCollection(array $models = []): VinylRecordCollection
  {
    return new VinylRecordCollection($models);
  }

  public function newEloquentBuilder($query): VinylRecordQueryBuilder
  {
    return new VinylRecordQueryBuilder($query);
  }
}
