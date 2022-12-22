<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

use App\Models\VinylRecord;
use App\Http\Controllers\Controller;
use App\Http\Requests\VinylRecord\CreateVinylRecordRequest;
use App\Http\Requests\VinylRecord\UpdateVinylRecordRequest;
use App\Http\Resources\VinylRecord\EditableResource as VinylRecordEditableResource;
use App\Models\Author;

class VinylRecordController extends Controller
{
  public function init()
  {
    $author_list = Author::query()->get(['id', 'name']);

    return compact('author_list');
  }


  public function get(VinylRecord $vinyl_record): VinylRecordEditableResource
  {
    return VinylRecordEditableResource::make($vinyl_record);
  }


  public function create(CreateVinylRecordRequest $request): VinylRecordEditableResource
  {
    $input = $request->only(['title', 'description']);

    $tag_ids    = $request->input('tag_ids', []);
    $author_ids = $request->input('author_ids', []);

    /** @var VinylRecord $vinyl_record */
    $vinyl_record = VinylRecord::create($input);
    $vinyl_record->tags()->attach($tag_ids);
    $vinyl_record->authors()->attach($author_ids);

    return VinylRecordEditableResource::make($vinyl_record);
  }


  public function update(UpdateVinylRecordRequest $request, VinylRecord $vinyl_record): VinylRecordEditableResource
  {
    $input = $request->only(['title', 'description']);

    $tag_ids    = $request->input('tag_ids', []);
    $author_ids = $request->input('author_ids', []);

    $vinyl_record->update($input);
    $vinyl_record->tags()->sync($tag_ids);
    $vinyl_record->authors()->sync($author_ids);

    return VinylRecordEditableResource::make($vinyl_record);
  }


  public function delete(VinylRecord $vinyl_record): JsonResponse
  {
    $vinyl_record->delete();

    return response()->json();
  }
}
