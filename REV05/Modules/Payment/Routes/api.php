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

Route::get('payment', 'PaymentAPIController@PaymentList')->middleware('auth:sanctum');
Route::get('paid', 'PaymentAPIController@Paid')->middleware('auth:sanctum');
Route::get('payment/{id}', 'PaymentAPIController@Checkout')->middleware('auth:sanctum');
