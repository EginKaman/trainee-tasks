<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Sockets\SocketUserController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Tournament\DuelController;
use App\Http\Controllers\Tournament\TournamentController;
use App\Http\Controllers\Tournament\UserController as TournamentUserController;
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
        Route::patch('orders/{order}', [OrderController::class, 'refund']);
        Route::post('payments', [PaymentController::class, 'store']);
        Route::post('payments/refund', [PaymentController::class, 'refund']);

        Route::get('cards', [CardController::class, 'index']);
        Route::delete('cards/{card}', [CardController::class, 'destroy']);

        Route::post('subscribe', [SubscribeController::class, 'subscribe']);
        Route::post('subscribe/cancel', [SubscribeController::class, 'cancel']);
    });

    Route::apiResource('users', UserController::class);
    Route::get('roles', RoleController::class);
    Route::get('products', ProductController::class);

    Route::post('payments/{method}/webhook', [PaymentController::class, 'webhook']);

    Route::get('subscriptions', [SubscriptionController::class, 'index']);

    Route::patch('sockets/users/{user}', [SocketUserController::class, 'update']);

    Route::get('domains', [DomainController::class, 'index']);

    Route::get('tournaments', TournamentController::class);
    Route::get('tournaments/{tournament}/duels', DuelController::class);
    Route::get('tournaments/{tournament}/users', TournamentUserController::class);
});
