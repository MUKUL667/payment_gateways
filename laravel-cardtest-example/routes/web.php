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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', 'HomeController@index');

Route::get('/', [App\Http\Controllers\HomeController::class, 'show']);

Auth::routes();
// Route::get('/public-key', 'HomeController@publickey')->middleware('auth');
// Route::post('/createcustomerinstripe/{email}', 'HomeController@CreateCustomerInStripe')->middleware('auth');
// Route::post('/chargethecustomer/{email}/{value2charge}', 'HomeController@ChargeTheCustomer')->middleware('auth');

Route::get('/public-key', [App\Http\Controllers\HomeController::class, 'publickey'])->middleware('auth');
Route::post('createcustomerinstripe/{email}', [App\Http\Controllers\HomeController::class, 'CreateCustomerInStripe'])->middleware('auth');
Route::post('/chargethecustomer/{email}/{value2charge}', [App\Http\Controllers\HomeController::class, 'ChargeTheCustomer'])->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

