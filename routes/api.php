<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

Route::prefix('v1')->middleware(['localization'])->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('verify', [LoginController::class, 'verify']);

    Route::post('callback', [SocialiteController::class, 'callback']);
    Route::post('social', [SocialiteController::class, 'social']);

    Route::middleware(['auth:api'])->group(function () {
        Route::patch('social/next', [SocialiteController::class, 'next']);
        Route::post('logout', [LoginController::class, 'logout']);
        Route::apiResource('orders', OrderController::class)->only([
            'store',
            'index'
        ]);
        Route::post('payments', [PaymentController::class, 'store']);
        Route::post('payments/refund', [PaymentController::class, 'refund']);
    });

    Route::apiResource('users', UserController::class);
    Route::get('roles', RoleController::class);
    Route::get('products', ProductController::class);

    Route::post('payments/{method}/webhook', [PaymentController::class, 'webhook']);
    Route::get('payments/{method}', [PaymentController::class, 'paymentSuccess']);
});

