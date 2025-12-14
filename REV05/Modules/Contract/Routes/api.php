<?php

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

Route::get('contract', 'ContractAPIController@ContractList')->middleware('auth:sanctum');
Route::get('/contract/signed-url/{file}', 'ContractAPIController@getSignedDownloadUrl')
    ->middleware('auth:sanctum')->name('contract.signed-url');

