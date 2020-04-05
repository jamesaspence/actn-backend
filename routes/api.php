<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/check', function () {
        return response([
            'user' => Auth::user()
        ]);
    });

    Route::post('/prices', 'PriceController@createPrice');
    Route::post('/purchases', 'PurchaseController@recordPurchase');

    Route::post('/logout', 'AuthController@logout');
});

Route::post('/login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register')->name('register');

Route::get('/heartbeat', function () {
    return response([
        'message' => 'success'
    ]);
});
