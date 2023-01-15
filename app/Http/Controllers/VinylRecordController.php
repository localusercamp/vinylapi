<?php

namespace App\Http\Controllers;

use App\Enums\Condition;
use App\Enums\VinylRecordStatus;
use Illuminate\Http\JsonResponse;

use App\Models\VinylRecord;
use App\Http\Controllers\Controller;
use App\Http\Resources\VinylRecord\EditableResource as VinylRecordEditableResource;
use App\Models\Author;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class VinylRecordController extends Controller
{
  protected array $steps = [
    'main', 'addition', 'media',
  ];



  public function listInit(Request $request)
  {
    $author_list = Author::query()->get(['id', 'name']);

    return compact('author_list');
  }

  public function list(Request $request)
  {
    $search = trim($request->input('q'));

    $is_valid_search = strlen($search) >= 3;

    $paginator = VinylRecord::query()
      ->with('poster', 'authors', 'country:id,name')
      ->when($is_valid_search, function ($query) use ($search) {
        $query->whereFullText('title', "'$search*'", ['mode' => 'boolean']);
        $query->orWhereFullText('hex', "'$search*'", ['mode' => 'boolean']);
        $query->orWhere('catalog_number', 'like', "%$search%");
      })
      ->whereHas('authors', function ($q) {
      })
      ->paginate();

    foreach ($paginator->items() as $vinyl_record) {
      $vinyl_record->append(['condition_abbreviation', 'sleeve_condition_abbreviation']);
    }

    return $paginator;
  }


  public function init()
  {
    $author_list = Author::query()->get(['id', 'name']);
    $condition_list = Condition::all();
    $genre_list = Genre::all();
    $country_list = Country::get(['id', 'name']);

    return compact('author_list', 'condition_list', 'genre_list', 'country_list');
  }

  public function get(VinylRecord $vinyl_record): VinylRecordEditableResource
  {
    return VinylRecordEditableResource::make($vinyl_record);
  }



  public function create(Request $request): VinylRecordEditableResource
  {
    return VinylRecordEditableResource::make(
      $this->updateOrCreateVinylRecord($request, new VinylRecord())
    );
  }

  public function update(Request $request, VinylRecord $vinyl_record): VinylRecordEditableResource
  {
    return VinylRecordEditableResource::make(
      $this->updateOrCreateVinylRecord($request, $vinyl_record)
    );
  }


  public function delete(VinylRecord $vinyl_record): JsonResponse
  {
    $vinyl_record->delete();

    return response()->json();
  }



  protected function updateOrCreateVinylRecord(Request $request, VinylRecord $vinyl_record): VinylRecord
  {
    $step = $request->input('step');

    if (!in_array($step, $this->steps)) abort(404);

    $input = $request->only(match ($step) {
      'main'     => ['title', 'description', 'price', 'author_ids'],
      'addition' => ['condition', 'sleeve_condition', 'release_year', 'country_id', 'genre_id', 'catalog_number'],
      'media'    => ['poster', 'gallery', 'publish', 'delete_gallery']
    });

    $errors = (match ($step) {
      'main'     => $this->validateMainStep($input),
      'addition' => $this->validateAdditionStep($input),
      'media'    => $this->validateMediaStep($input),
    })->errors();

    if ($errors->isNotEmpty()) response()->json(compact('errors'), 422)->throwResponse();

    if ($step === 'main') {
      $vinyl_record->fill(Arr::except($input, 'author_ids'));
      $vinyl_record->save();
      $vinyl_record->setHex();
      $vinyl_record->save();
      $main_author = array_shift($input['author_ids']);
      $vinyl_record->authors()->syncWithPivotValues($input['author_ids'], ['is_main' => false]);
      $vinyl_record->authors()->syncWithPivotValues([$main_author], ['is_main' => true], false);
    } else if ($step === 'addition') {
      $vinyl_record->fill($input)->save();
    } else if ($step === 'media') {
      $uploadedPoster = $input['poster'] ?? null;
      $uploadedGallery = $input['gallery'] ?? [];
      $deleteGalleryIds = $input['delete_gallery'] ?? [];
      $galleryIds = $vinyl_record->gallery()->pluck('id');

      if (!$vinyl_record->poster && !$uploadedPoster) response()->json(new MessageBag([
        'poster' => 'Необходимо загрузить постер'
      ]))->throwResponse();

      if ($vinyl_record->poster && $uploadedPoster) {
        $vinyl_record->poster->deleteWithSource();
      }

      if ($uploadedPoster) {
        $path = $uploadedPoster->storePublicly('public/vinyl-record/posters');
        $poster = Image::create(compact('path'));
        $vinyl_record->poster_id = $poster->id;
      }

      foreach ($deleteGalleryIds as $id) {
        if (!$galleryIds->contains($id)) abort(404);
        Image::find($id)->deleteWithSource();
      }

      foreach ($uploadedGallery as $uploadedImage) {
        $path = $uploadedImage->storePublicly('public/vinyl-record/galleries/' . $vinyl_record->id);
        $vinyl_record->gallery()->create(compact('path'));
      }

      if ($input['publish']) $vinyl_record->status = VinylRecordStatus::Published->name;

      $vinyl_record->save();
    }

    return $vinyl_record;
  }



  protected function validateMainStep(array $input): Validator
  {
    return validator($input, [
      'title'       => 'required|string',
      'description' => 'nullable|string',
      'price'       => 'required|integer|min:0',
      'author_ids'  => 'required|array',
    ]);
  }

  protected function validateAdditionStep(array $input): Validator
  {
    return validator($input, [
      'condition'        => 'sometimes|nullable|string',
      'sleeve_condition' => 'sometimes|nullable|string',
      'release_year'     => 'sometimes|nullable|integer',
      'country_id'       => 'sometimes|nullable|integer',
      'genre_id'         => 'sometimes|nullable|integer',
      'catalog_number'   => 'sometimes|nullable|string',
    ]);
  }

  protected function validateMediaStep(array $input): Validator
  {
    return validator($input, [
      'poster' => 'sometimes',
      'gallery' => 'sometimes|array',
      'publish' => 'required|bool',
      'delete_gallery' => 'sometimes|array'
    ]);
  }
}
