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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/payments/pay',[App\Http\Controllers\PaymentController::class, 'pay'])->name('pay');
Route::get('/payments/approval',[App\Http\Controllers\PaymentController::class, 'approval'])->name('approval');
Route::get('/payments/cancelled',[App\Http\Controllers\PaymentController::class, 'cancelled'])->name('cancelled');

Route::prefix('subscribe')
        ->name('subscribe.')
        ->group(function(){
            Route::get('/','SubscriptionController@show')
            ->name('show');

            Route::post('/','SubscriptionController@store')
            ->name('store');

            Route::get('/approval','SubscriptionController@approval')
            ->name('approval');

            Route::get('/cancelled','SubscriptionController@cancelled')
            ->name('cancelled');
        });
