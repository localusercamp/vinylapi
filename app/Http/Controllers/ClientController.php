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
      ->orderByDesc('created_at')
      ->take(8)
      ->get();

    return compact('new');
  }


  public function vinyl(VinylRecord $vinyl_record)
  {
    return $vinyl_record
      ->load(['poster', 'gallery', 'authors', 'genre', 'country'])
      ->append(['condition_abbreviation', 'sleeve_condition_abbreviation']);
  }
}
