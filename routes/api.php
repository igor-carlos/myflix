<?php

use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeasonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/series', [SeriesController::class, 'index']);
Route::prefix('/v1/serie')->group(function () {
    Route::post('/', [SeriesController::class, 'store']);
    Route::get('/{id}', [SeriesController::class, 'show']);
    Route::patch('/{id}', [SeriesController::class, 'update']);
    Route::put('/{id}/status', [SeriesController::class, 'status']);
    Route::delete('/{id}', [SeriesController::class, 'destroy']);
});

Route::get('/v1/temporadas', [SeasonController::class, 'index']);
Route::prefix('/v1/temporada')->group(function () {
    Route::post('/', [SeasonController::class, 'store']);
    Route::get('/{id}', [SeasonController::class, 'showBySerie']);
    Route::put('/{id}', [SeasonController::class, 'updateBySerie']);
    Route::delete('/{id}', [SeasonController::class, 'destroy']);
});
