<?php

namespace App\Models;

use App\QueryBuilders\GenreQueryBuilder;
use App\Collections\GenreCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static GenreQueryBuilder query()
 */
class Genre extends Model
{
  public $timestamps = false;


  public function newCollection(array $models = []): GenreCollection
  {
    return new GenreCollection($models);
  }

  public function newEloquentBuilder($query): GenreQueryBuilder
  {
    return new GenreQueryBuilder($query);
  }
}
