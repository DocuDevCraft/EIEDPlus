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

Route::get('work-package/send-offer/{id}', 'FreelancerOfferAPIController@show')->middleware('auth:sanctum');
Route::post('work-package/send-offer-signature-request', 'OfferContract\OfferContractController@offerContractSignatureRequest')->middleware('auth:sanctum');
Route::post('work-package/send-offer/{id}', 'FreelancerOfferAPIController@storeOffer')->middleware('auth:sanctum');
