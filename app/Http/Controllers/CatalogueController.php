<?php

namespace App\Http\Controllers;

use App\Enums\Condition;
use App\Enums\VinylRecordStatus;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Country;
use App\Models\Genre;
use App\Models\VinylRecord;

class CatalogueController extends Controller
{
  public function init(Request $request)
  {
    $author_list = Author::query()->get(['id', 'name']);
    $condition_list = Condition::all();
    $genre_list = Genre::all();
    $country_list = Country::get(['id', 'name']);

    return compact('author_list', 'condition_list', 'genre_list', 'country_list');
  }

  public function index(Request $request)
  {
    // header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Methods: *');
    // header('Access-Control-Allow-Headers: *');

    $input = $request->only(['q', 'author', 'genre', 'condition', 'country', 'yearMin', 'yearMax']);

    $errors = validator($input, [
      'q'           => 'sometimes|nullable|string',
      'sort'        => 'sometimes|nullable|string',
      'author'      => 'sometimes|nullable|array',
      'author.*'    => 'integer',
      'genre'       => 'sometimes|nullable|array',
      'genre.*'     => 'integer',
      'condition'   => 'sometimes|nullable|array',
      'condition.*' => 'string',
      'priceMin'    => 'sometimes|nullable|integer',
      'priceMax'    => 'sometimes|nullable|integer',
    ])->errors();

    if ($errors->isNotEmpty()) response()->json(compact('errors'), 422)->throwResponse();

    $q         = $request->input('q');
    $sort      = $request->input('sort');
    $author    = $request->collect('author');
    $genre     = $request->collect('genre');
    $condition = $request->collect('condition');
    $priceMin  = $request->input('priceMin');
    $priceMax  = $request->input('priceMax');

    return VinylRecord::query()
      ->with(['poster', 'authors'])
      ->where('status', VinylRecordStatus::Published->name)
      ->when($q, fn ($query) => $query
        ->whereFullText('title', "'$q*'", ['mode' => 'boolean'])
        ->orWhere('catalog_number', 'like', "%$q%"))
      ->when($author->isNotEmpty(), fn ($query) => $query
        ->whereHas('authors', fn ($query) => $query
          ->whereKey($author)))
      ->when($genre->isNotEmpty(), fn ($query) => $query
        ->whereIn('genre_id', $genre))
      ->when($condition->isNotEmpty(), fn ($query) => $query
        ->whereIn('condition', $condition))
      ->when(!is_null($priceMin), fn ($query) => $query
        ->where('price', '>=', intval($priceMin)))
      ->when(!is_null($priceMax), fn ($query) => $query
        ->where('price', '<=', intval($priceMax)))
      ->when($sort, fn ($query) => match ($sort) {
        'price-asc'  => $query->orderBy('price', 'asc'),
        'price-desc' => $query->orderBy('price', 'desc'),
        'date-asc'   => $query->orderBy('created_at', 'asc'),
        'date-desc'  => $query->orderBy('created_at', 'desc'),
        default => $query
      })
      ->paginate(10);
  }
}
