<?php

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
Route::get('/workplace', [\App\Http\Controllers\WebController::class, 'workplace']);

Route::prefix('user')->group(function () {
    Route::get('/', [\App\Http\Controllers\WebController::class, 'user']);
    Route::delete('/{id}', [\App\Http\Controllers\WebController::class, 'removeUser']);
    Route::post('/{id}/status', [\App\Http\Controllers\WebController::class, 'userStatus']);
});

Route::prefix('driver')->group(function () {
    Route::get('/', [\App\Http\Controllers\WebController::class, 'driver']);
    Route::delete('/{id}', [\App\Http\Controllers\WebController::class, 'removeDriver']);
    Route::post('/{id}/status', [\App\Http\Controllers\WebController::class, 'driverStatus']);
});

Route::prefix('price')->group(function () {
    Route::get('/all', [\App\Http\Controllers\WebController::class, 'allPrice']);
    Route::post('/', [\App\Http\Controllers\WebController::class, 'changePrice']);
});

Route::prefix('order')->group(function () {
    Route::get('/', [\App\Http\Controllers\WebController::class, 'order']);
});

