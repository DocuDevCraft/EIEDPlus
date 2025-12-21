<?php

use Illuminate\Http\Request;
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

Route::options('{any}', function(){
	return response()->json([], 200);
})->where('any', '.*');

Route::post('login', 'App\Http\Controllers\UserController@login');
Route::post('login-verify', 'App\Http\Controllers\UserController@loginVerify');
Route::post('forget-password', [\App\Http\Controllers\AuthAPI\ForgetPasswordAPIController::class, 'forgetPassword'])->name('forget-password-api');
Route::post('reset-password', [\App\Http\Controllers\AuthAPI\ForgetPasswordAPIController::class, 'resetPassword'])->name('reset-password-api');
Route::post('verify-code', 'App\Http\Controllers\UserController@verify');
Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('logout', 'App\Http\Controllers\UserController@logout')->middleware('auth:sanctum');
Route::get('user-data', 'App\Http\Controllers\UserController@userData')->middleware('auth:sanctum');
