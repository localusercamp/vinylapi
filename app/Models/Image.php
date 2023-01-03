<?php

namespace App\Models;

use App\Collections\ImageCollection;
use App\QueryBuilders\ImageQueryBuilder;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static ImageQueryBuilder query()
 */
class Image extends Model
{
  public function url()
  {
    return new Attribute(
      get: fn ($value, $attributes) => public_path($attributes['path'])
    );
  }



  public function newCollection(array $models = []): ImageCollection
  {
    return new ImageCollection($models);
  }

  public function newEloquentBuilder($query): ImageQueryBuilder
  {
    return new ImageQueryBuilder($query);
  }
}
