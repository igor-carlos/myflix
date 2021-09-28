<?php

use App\Http\Controllers\SeriesController;
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
    Route::put('/{id}', [SeriesController::class, 'update']);
    Route::delete('/{id}', [SeriesController::class, 'destroy']);
});
