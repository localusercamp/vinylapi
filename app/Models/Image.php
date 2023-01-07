<?php

namespace App\Models;

use App\Collections\ImageCollection;
use App\QueryBuilders\ImageQueryBuilder;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static ImageQueryBuilder query()
 */
class Image extends Model
{
  protected $fillable = [
    'path',
    'vinyl_record_id'
  ];

  protected $hidden = [
    'path'
  ];

  protected $appends = [
    'url'
  ];


  public function url(): Attribute
  {
    return new Attribute(
      get: fn ($value, $attributes) => asset(str($attributes['path'])->replaceFirst('public', 'storage'))
    );
  }



  public function deleteWithSource()
  {
    Storage::delete($this->path);
    $this->delete();
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
