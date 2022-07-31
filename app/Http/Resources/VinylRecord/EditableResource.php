<?php

namespace App\Http\Resources\VinylRecord;

use Illuminate\Http\Resources\Json\JsonResource;

class EditableResource extends JsonResource
{
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'tag_ids' => $this->tags->pluck('id'),
      'author_ids' => $this->authors->pluck('id'),
    ];
  }
}
