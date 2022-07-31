<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\QueryBuilders\AuthorQueryBuilder;
use App\Collections\AuthorCollection;
use App\Models\Pivots\AuthorVinylRecord;

/**
 * @method static AuthorQueryBuilder query()
 */
class Author extends Model
{
  use HasFactory, SoftDeletes;


  public function vinyl_records(): BelongsToMany
  {
    return $this->belongsToMany(VinylRecord::class)->using(AuthorVinylRecord::class);
  }


  public function newCollection(array $models = []): AuthorCollection
  {
    return new AuthorCollection($models);
  }

  public function newEloquentBuilder($query): AuthorQueryBuilder
  {
    return new AuthorQueryBuilder($query);
  }
}
