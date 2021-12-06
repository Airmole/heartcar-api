<?php

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

Route::post('/register', [\App\Http\Controllers\IndexController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\IndexController::class, 'login']);

// 订单相关接口
Route::prefix('order')->group(function () {
    Route::get('/', [\App\Http\Controllers\OrderController::class, 'index']);
    Route::get('/my', [\App\Http\Controllers\OrderController::class, 'my']);
    Route::post('/', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\OrderController::class, 'show']);
    Route::get('/{id}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel']);
});

// 微信小程序接口
Route::prefix('wechat')->group(function () {
    Route::get('openid', [\App\Http\Controllers\WechatController::class, 'getOpenid']);
    Route::post('login', [\App\Http\Controllers\WechatController::class, 'login']);
});
