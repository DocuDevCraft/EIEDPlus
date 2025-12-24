<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('work-package/signature-digest-action/{id}', 'WorkPackageManagerAPIController@workPackageSignatureDigestAction');

Route::get('/', function () {
    return redirect("/login");
})->name('home');

Route::get('/email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

Auth::routes();
