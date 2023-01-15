<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VinylRecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->group(function () {

Route::prefix('admin-panel')->group(function () {
  // Route::post('upload-vinyl-cover', [ImageController::class, 'uploadVinylRecordCover']);
  // Route::post('test', [ImageController::class, 'test']);
});

Route::prefix('admin')->group(function () {
  Route::apiResource('author', AuthorController::class)->only(['index', 'store', 'update', 'destroy']);
  Route::put('author/{author}/restore', [AuthorController::class, 'restore'])->withTrashed();

  Route::prefix('vinyl-record')->controller(VinylRecordController::class)->group(function () {
    Route::get('list-init',       'listInit');
    Route::get('',                'list');
    Route::get('init',            'init');
    Route::get('{vinyl_record}',  'get');
    Route::post('',               'create');
    Route::match(['put', 'post'], '{vinyl_record}', 'update');
  });
  // Route::post('upload-vinyl-cover', [ImageController::class, 'uploadVinylRecordCover']);
  // Route::post('test', [ImageController::class, 'test']);
});
// });
