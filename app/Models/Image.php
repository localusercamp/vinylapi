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
  public const VINYL_RECORD_POSTERS_DIR = 'public/vinyl-record/posters';
  public const VINYL_RECORD_XS_POSTERS_DIR = 'public/vinyl-record/xs-posters';

  public const VINYL_RECORD_GALLERIES_DIR = 'public/vinyl-record/galleries';


  protected $fillable = [
    'path',
    'xs_path',
    'vinyl_record_id',
  ];

  protected $hidden = [
    'path',
    'xs_path',
  ];

  protected $appends = [
    'url',
    'xs_url',
  ];


  public function url(): Attribute
  {
    return new Attribute(
      get: fn ($value, $attributes) => asset(str($attributes['path'])->replaceFirst('public', 'storage'))
    );
  }

  public function xsUrl(): Attribute
  {
    return new Attribute(
      get: fn ($value, $attributes) => $attributes['xs_path']
        ? asset(str($attributes['xs_path'])->replaceFirst('public', 'storage'))
        : null
    );
  }



  public function makeXsVersion(): static
  {
    if (!$this->path || $this->xs_path) return $this;

    $source = Storage::path($this->path);

    ['mime' => $mime] = getimagesize($source);

    $image = match ($mime) {
      'image/jpeg' => imagecreatefromjpeg($source),
      'image/png'  => imagecreatefrompng($source),
      default => false
    };

    $xs_path = str($this->path)->replace('vinyl-record/posters', 'vinyl-record/xs-posters')->value();

    Storage::put($xs_path, '');

    $w = $h = 256;

    $old_w = imagesx($image);
    $old_h = imagesy($image);

    if ($old_w > $w * 2 || $old_h > $h * 2) {
      $temp = imagecreatetruecolor($w, $h);
      imagecopyresampled($temp, $image, 0, 0, 0, 0, $w, $h, $old_w, $old_h);
      $image = $temp;
    }

    imagejpeg($image, Storage::path($xs_path), 50);

    $this->xs_path = $xs_path;

    return $this;
  }



  public function deleteWithSource()
  {
    Storage::delete($this->path);
    if ($this->xs_path) Storage::delete($this->xs_path);
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
