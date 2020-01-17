<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// clients routes
Route::prefix('clients')->group(function () {
    Route::get('/', 'ClientController@getClients');
    Route::get('/{id}', 'ClientController@getClient');
    Route::get('/{clientId}/payments', 'ClientController@getClientPayments');
    Route::get('{clientId}/accounts', 'ClientController@getClientAccounts');
    Route::post('/create', 'ClientController@createClient');
});

// accounts routes
Route::prefix('accounts')->group(function () {
    Route::get('/', 'AccountController@getAccounts');
    Route::get('/{id}', 'AccountController@getAccount');
    Route::get('/{accountId}/payments', 'AccountController@getAccountPayments');
    Route::post('/create', 'AccountController@createAccount');
});

// payments routes
Route::prefix('payments')->group(function () {
    Route::post('/create', 'PaymentController@createPayment');
    Route::post('/approve', 'PaymentController@approvePayments');
});
