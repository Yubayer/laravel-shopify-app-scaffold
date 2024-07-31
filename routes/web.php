<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    IndexController,
    WehbookResponseController,
    BillingController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('welcome');
// })->middleware(['verify.shopify'])->name('home');

Route::group(['middleware' => ['verify.shopify']], function() {
    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/app-billing-view', [BillingController::class, 'appBilling'])->name('app.billing-view');
});


// wehbook controllers
Route::group(['middleware' => ['auth.webhook']], function() {
    Route::post(env('WEBHOOK_TOPICS_URL_1', '/webhook/topics/all'), [WehbookResponseController::class, 'webhookTopics']);
    Route::post(env('WEBHOOK_TOPICS_URL_2', '/public/webhook/topics/all'), [WehbookResponseController::class, 'webhookTopics']);
});
