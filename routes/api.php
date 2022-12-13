<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ImageController;
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

  // Route::post('upload-vinyl-cover', [ImageController::class, 'uploadVinylRecordCover']);
  // Route::post('test', [ImageController::class, 'test']);
});
// });
