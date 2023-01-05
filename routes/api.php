<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\QuoteController;

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

Route::post('/sanctum/token', [QuoteController::class, 'token'])->name('token');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user())->name('api.user');
    Route::get('/get', [QuoteController::class, 'get'])->name('api.get');
    Route::post('/add-favorite', [QuoteController::class, 'addFavorite'])->name('api.add_favorite');
    Route::get('/favorites', [QuoteController::class, 'getFavorites'])->name('api.get_favorites');
    Route::delete('/favorites/{favorite}', [QuoteController::class, 'deleteFavorite'])
        ->name('api.delete_favorite');
});
