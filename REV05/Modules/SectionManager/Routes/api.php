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

/* Section Handler API */
Route::get('section-list/{level}/{id}', 'SectionAPIHandlerController@getList')->middleware('auth:sanctum');
