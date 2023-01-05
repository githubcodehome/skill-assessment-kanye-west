<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [QuoteController::class, 'main'])->name('main');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/favorites', [QuoteController::class, 'favorites'])
        ->name('favorites');
});

require __DIR__ . '/auth.php';
