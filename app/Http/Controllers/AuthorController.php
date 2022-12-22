<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
  public function index(Request $request)
  {
    validator($request->all(), [
      'q' => 'sometimes|nullable|string',
      'tab' => 'sometimes|in:active,trash'
    ])->validate();

    $q   = $request->input('q');
    $tab = $request->input('tab');

    return Author::query()
      ->when($q, fn ($query) => $query->where('name', 'like', "%$q%"))
      ->when($tab === 'trash', fn ($query) => $query->onlyTrashed())
      ->withCount('vinyl_records')
      ->get();
  }



  public function store(Request $request)
  {
    $input = $request->only(['name']);

    $errors = validator($input, [
      'name' => 'required|string|unique:authors,name|max:128'
    ])->errors();

    if ($errors->isNotEmpty()) return response()->json(compact('errors'), 422);

    Author::create($input);
  }



  public function update(Request $request, Author $author)
  {
    $input = $request->only(['name']);

    $errors = validator($input, [
      'name' => 'required|string|unique:authors,name|max:128'
    ])->errors();

    if ($errors->isNotEmpty()) return response()->json(compact('errors'), 422);

    $author->update($input);
  }



  public function destroy(Request $request, Author $author)
  {
    if ($author->vinyl_records()->count() !== 0) abort(422);

    $author->delete();
  }



  public function restore(Request $request, Author $author)
  {
    $author->restore();
  }
}
