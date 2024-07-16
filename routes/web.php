<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WehbookResponseController;

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
});

// wehbook controllers
Route::post('/webhook/app/uninstalled', [WehbookResponseController::class, 'appUninstalled']);
Route::post('/webhook/orders/paid', [WehbookResponseController::class, 'ordersPaid']);
Route::post('/webhook/carts/update', [WehbookResponseController::class, 'cartsUpdate']);
