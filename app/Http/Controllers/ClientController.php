<?php

namespace App\Http\Controllers;

use App\Enums\VinylRecordStatus;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\VinylRecord;

class ClientController extends Controller
{
  public function home(Request $request)
  {
    $new = VinylRecord::query()
      ->with(['poster', 'authors'])
      ->where('status', VinylRecordStatus::Published->name)
      ->orderBy('created_at')
      ->get();

    $new = $new->concat($new)->take(7);

    return compact('new');
  }


  public function catalogue(Request $request)
  {
    $input = $request->only(['q', 'author', 'genre', 'condition', 'country', 'yearMin', 'yearMax']);

    $errors = validator($input, [
      'q'         => 'sometimes|string',
      'author'    => 'sometimes|integer',
      'genre'     => 'sometimes|integer',
      'condition' => 'sometimes|integer',
      'country'   => 'sometimes|integer',
      'yearMin'   => 'sometimes|integer',
      'yearMax'   => 'sometimes|integer',
    ])->errors();

    if ($errors->isNotEmpty()) abort(422);

    $q = $request->input('q');

    return VinylRecord::query()
      ->with(['poster', 'authors'])
      ->where('status', VinylRecordStatus::Published->name)
      ->when($request->filled('q'), fn ($query) => $query
        ->whereFullText('title', "'$q*'", ['mode' => 'boolean'])
        ->orWhere('catalog_number', 'like', "%$q%"))
      ->paginate(10);
  }


  public function vinyl(VinylRecord $vinyl_record)
  {
    return $vinyl_record->load(['poster', 'gallery', 'authors']);
  }
}
