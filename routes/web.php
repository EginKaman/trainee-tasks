<?php

use App\Http\Controllers\AsyncApiController;
use App\Http\Controllers\Book\BotController;
use App\Http\Controllers\Book\BotDataController;
use App\Http\Controllers\Book\CategoryController;
use App\Http\Controllers\Book\JobController;
use App\Http\Controllers\Book\JobDataController;
use App\Http\Controllers\Book\SiteController;
use App\Http\Controllers\Book\WorkerController;
use App\Http\Controllers\Book\WorkerDataController;
use App\Http\Controllers\ConvertorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\Images\OptimizerController;
use App\Http\Controllers\SearchController;
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

Route::get('form', [FormController::class, 'index'])->name('form.index');
Route::post('form', [FormController::class, 'store'])->name('form.store');

Route::get('{driver}/callback', function () {
    return view('socialite.callback');
});

Route::get('payments', function () {
    return view('payments.form');
});

Route::get('payments/{method}/success', [StripePaymentController::class, 'success'])->name('payments.stripe.success');
Route::get('payments/{method}/cancel', [StripePaymentController::class, 'cancel'])->name('payments.stripe.cancel');
Route::get('async-api', [AsyncApiController::class, 'index']);
Route::get('search', [SearchController::class, 'elastic'])->name('search');


Route::resource('sites', SiteController::class)->whereUuid(['site']);
Route::resource('sites.categories', CategoryController::class)->shallow()->whereUuid(['site', 'category']);
Route::get('sites/{site}/categories/{category}/bots/data', BotDataController::class)->name('bots.data');
Route::resource('sites.categories.bots', BotController::class)
    ->shallow()
    ->only(['index', 'destroy'])
    ->whereUuid(['site', 'category', 'bot']);
Route::get('bots/{bot}/jobs/data', JobDataController::class)->name('bots.jobs.data')->whereUuid(['bot']);
Route::resource('bots.jobs', JobController::class)
    ->shallow()->except(['show'])->whereUuid(['bot', 'job']);
Route::get('jobs/{job}/workers/data', WorkerDataController::class)->name('jobs.workers.data')->whereUuid(['job']);
Route::resource('jobs.workers', WorkerController::class)->shallow()->whereUuid(['job', 'worker']);
