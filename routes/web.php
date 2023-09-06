<?php

use App\Http\Controllers\ConvertorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Images\OptimizerController;
use App\Http\Controllers\Sendgrid\WebhookController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Middleware\SignedWebhookMiddleware;
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
Route::get('/feedback', [FeedbackController::class, 'index'])
    ->name('feedback');
Route::post('/feedback', [FeedbackController::class, 'store']);
Route::post('/sendgrid/webhook', [WebhookController::class, 'update'])
    ->middleware(SignedWebhookMiddleware::class);

Route::get('/convertor', [ConvertorController::class, 'index'])
    ->name('convertor');
Route::post('/convertor', [ConvertorController::class, 'store']);
Route::get('/schema.json', [ConvertorController::class, 'jsonSchema'])
    ->name('convertor.json-schema');
Route::get('/schema.xsd', [ConvertorController::class, 'xmlSchema'])
    ->name('convertor.xml-schema');
Route::prefix('images')->group(function () {
    Route::get('optimizer', [OptimizerController::class, 'index'])->name('optimizer');
    Route::get('optimizer/test', [OptimizerController::class, 'test'])->name('optimizer.test');
    Route::get('optimizer/previous', [OptimizerController::class, 'previous'])->name('optimizer.previous');
    Route::get('optimizer/{image:hash}', [OptimizerController::class, 'show'])->name('optimizer.show');
    Route::post('optimizer', [OptimizerController::class, 'store']);
})->name('images');

Route::get('form', [\App\Http\Controllers\FormController::class, 'index'])->name('form.index');
Route::post('form', [\App\Http\Controllers\FormController::class, 'store'])->name('form.store');

Route::get('{driver}/callback', function () {
    return view('socialite.callback');
});

Route::get('payments', function () {
    return view('payments.form');
});

Route::get('payments/{method}/success', [StripePaymentController::class, 'success'])->name('payments.stripe.success');
Route::get('payments/{method}/cancel', [StripePaymentController::class, 'cancel'])->name('payments.stripe.cancel');
Route::get('async-api', [\App\Http\Controllers\AsyncApiController::class, 'index']);
Route::get('search', [\App\Http\Controllers\SearchController::class, 'elastic'])->name('search');
