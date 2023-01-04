<?php

namespace App\Models;

use App\QueryBuilders\CountryQueryBuilder;
use App\Collections\CountryCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static CountryQueryBuilder query()
 */
class Country extends Model
{
  public $timestamps = false;


  public function newCollection(array $models = []): CountryCollection
  {
    return new CountryCollection($models);
  }

  public function newEloquentBuilder($query): CountryQueryBuilder
  {
    return new CountryQueryBuilder($query);
  }
}
