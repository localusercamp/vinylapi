<?php

namespace App\Http\Requests\VinylRecord;

use Illuminate\Foundation\Http\FormRequest;

class CreateVinylRecordRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'title'       => ['required', 'string', 'max:128'],
      'description' => ['nullable', 'string'],
      'tag_ids'     => ['present', 'array'],
      'author_ids'  => ['present', 'array'],
    ];
  }
}
