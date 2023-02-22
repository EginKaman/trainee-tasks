<?php

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

Route::prefix('v1')->middleware(['localization'])->group(function () {
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout']);
    });

    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::get('roles', \App\Http\Controllers\RoleController::class);
});

