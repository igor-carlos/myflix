<?php

use App\Http\Controllers\SeriesController;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\EpisodiosController;
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
    Route::patch('/last-episode/{id}', [SeriesController::class, 'updateLastEpisode']);
    Route::put('/{id}/status', [SeriesController::class, 'status']);
    Route::delete('/{id}', [SeriesController::class, 'destroy']);
});

Route::get('/v1/temporadas', [TemporadasController::class, 'index']);
Route::prefix('/v1/temporada')->group(function () {
    Route::post('/', [TemporadasController::class, 'store']);
    Route::get('/{id}', [TemporadasController::class, 'showBySerie']);
    Route::put('/{id}', [TemporadasController::class, 'updateBySerie']);
    Route::delete('/{id}', [TemporadasController::class, 'destroy']);
});

Route::get('/v1/episodios', [EpisodiosController::class, 'index']);
Route::prefix('/v1/episodio')->group(function () {
    Route::post('/', [EpisodiosController::class, 'store']);
    Route::get('/{id}', [EpisodiosController::class, 'show']);
    Route::put('/{id}', [EpisodiosController::class, 'update']);
    Route::delete('/{id}', [EpisodiosController::class, 'destroy']);
});
