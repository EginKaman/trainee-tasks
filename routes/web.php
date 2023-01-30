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

Route::get('/', function () {
    return redirect()->route('feedback');
});
Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'index'])
    ->name('feedback');
Route::post('/feedback', [\App\Http\Controllers\FeedbackController::class, 'store']);
Route::post('/sendgrid/webhook', [\App\Http\Controllers\Sendgrid\WebhookController::class, 'update']);
