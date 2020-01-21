<?php

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

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('billing', 'BillingController@index')->name('billing');
    Route::get('checkout/{plan_id}', 'CheckoutController@checkout')->name('checkout');
    Route::post('checkout', 'CheckoutController@processCheckout')->name('checkout.process');
    Route::get('cancel', 'BillingController@cancel')->name('cancel');
    Route::get('resume', 'BillingController@resume')->name('resume');

    Route::get('payment-methods/default/{paymentMethod}', 'PaymentMethodController@markDefault')->name('payment-methods.markDefault');
    Route::resource('payment-methods', 'PaymentMethodController');
    Route::get('invoices/download/{paymentId}', 'BillingController@downloadInvoice')->name('invoices.download');

    Route::resource('tasks', 'TaskController');
});

Route::stripeWebhooks('stripe-webhook');
