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

    $new = $new->concat($new)->take(8);

    return compact('new');
  }


  public function vinyl(VinylRecord $vinyl_record)
  {
    return $vinyl_record->load(['poster', 'gallery', 'authors', 'genre', 'country']);
  }
}
