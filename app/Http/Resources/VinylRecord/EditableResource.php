<?php

namespace App\Http\Resources\VinylRecord;

use Illuminate\Http\Resources\Json\JsonResource;

class EditableResource extends JsonResource
{
  public static $wrap = false;

  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'price' => $this->price,
      'description' => $this->description,
      'status' => $this->status,
      'condition' => $this->condition,
      'sleeve_condition' => $this->sleeve_condition,
      'release_year' => $this->release_year,
      'catalog_number' => $this->catalog_number,

      'poster' => $this->poster,
      'gallery' => $this->gallery,

      'genre_id' => $this->genre_id,
      'country_id' => $this->country_id,

      'author_ids' => $this->authors->pluck('id'),
    ];
  }
}
