<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\StripeController;
use App\Http\Controllers\PaymentController;

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
    return view('welcome');
});
// Route::get('/stripe-payment', [StripeController::class, 'handleGet']);
// Route::post('/stripe-payment', [StripeController::class, 'handlePost'])->name('stripe.payment');



/////
Route::get('/payment', [PaymentController::class, 'index']);
Route::post('/charge', [PaymentController::class, 'charge']);
Route::get('/confirm', [PaymentController::class, 'confirm']);
