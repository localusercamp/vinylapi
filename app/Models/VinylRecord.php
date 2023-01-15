<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
  BelongsToMany,
  HasMany,
  HasOne,
  HasOneThrough,
};

use App\QueryBuilders\VinylRecordQueryBuilder;
use App\Collections\VinylRecordCollection;
use App\Enums\Condition;
use App\Interfaces\Sellable;
use App\Models\Pivots\{
  TagVinylRecord,
  AuthorVinylRecord,
};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @method static VinylRecordQueryBuilder query()
 */
class VinylRecord extends Model implements Sellable
{
  use HasFactory, SoftDeletes;

  protected $guarded = [
    'id',
  ];

  protected $hidden = [
    'pivot',
  ];

  protected $casts = [
    'condition' => Condition::class,
    'sleeve_condition' => Condition::class,
  ];



  public function conditionAbbreviation(): Attribute
  {
    return new Attribute(
      get: fn () => $this->condition?->abbreviation()
    );
  }

  public function sleeveConditionAbbreviation(): Attribute
  {
    return new Attribute(
      get: fn () => $this->sleeve_condition?->abbreviation()
    );
  }



  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class)->using(TagVinylRecord::class);
  }

  public function author(): HasOneThrough
  {
    return $this->hasOneThrough(Author::class, AuthorVinylRecord::class, 'vinyl_record_id', 'id', 'id', 'author_id')->where('is_main', true);
  }

  public function authors(): BelongsToMany
  {
    return $this->belongsToMany(Author::class)->using(AuthorVinylRecord::class)->orderByPivot('is_main', 'desc');
  }

  public function poster()
  {
    return $this->belongsTo(Image::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  public function gallery(): HasMany
  {
    return $this->hasMany(Image::class, 'vinyl_record_id');
  }


  public function setHex()
  {
    $this->hex = strtoupper(dechex($this->id));
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
