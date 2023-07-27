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
use Illuminate\Pagination\Paginator;

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

    $query = VinylRecord::query()
      ->with(['poster', 'authors'])
      ->where('status', VinylRecordStatus::Published->name);

    if ($q) {
      $query->where(function ($query) {
        $query->whereFullText('title', "'$q*'", ['mode' => 'boolean'])->orWhere('catalog_number', 'like', "%$q%");
      });
    }

    if ($author->isNotEmpty()) {
      $query->whereHas('authors', fn ($query) => $query->whereKey($author));
    }

    if ($genre->isNotEmpty()) {
      $query->whereIn('genre_id', $genre);
    }

    if ($condition->isNotEmpty()) {
      $query->whereIn('condition', $condition);
    }

    if (!is_null($priceMin)) {
      $query->where('price', '>=', intval($priceMin));
    }

    if (!is_null($priceMax)) {
      $query->where('price', '<=', intval($priceMax));
    }

    if ($sort) {
      $sorts = collect([
        'price-asc'  => fn () => $query->orderBy('price', 'asc'),
        'price-desc' => fn () => $query->orderBy('price', 'desc'),
        'date-asc'   => fn () => $query->orderBy('created_at', 'asc'),
        'date-desc'  => fn () => $query->orderBy('created_at', 'desc'),
      ]);

      if ($sorts->keys()->contains($sort)) ($sorts->get($sort))();
    }

    return $query->paginate(12)->onEachSide(1);
  }
}
