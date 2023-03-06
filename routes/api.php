<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductsController;
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
        Route::apiResource('orders', OrdersController::class)->only([
            'store',
            'index'
        ]);
        Route::post('payments', [PaymentsController::class, 'store']);
        Route::post('payments/refund', [PaymentsController::class, 'refund']);
    });

    Route::apiResource('users', UserController::class);
    Route::get('roles', RoleController::class);
    Route::get('products', ProductsController::class);

    Route::post('payments/{method}/webhook', [PaymentsController::class, 'webhook']);
    Route::get('payments/{method}', [PaymentsController::class, 'paymentSuccess']);
});

