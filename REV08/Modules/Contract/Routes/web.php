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

Route::get('/download/contract/employer-signed/{file}', 'ContractAPIController@download')
    ->name('freelancer.contract.download');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['can:isAdmin']], function () {
        Route::prefix('dashboard')->group(function () {
            Route::resource('contract', 'ContractController');
            Route::get('/download/contract/employer/{path}/{file}', 'ContractController@download')->name('contract.employer.download');
        });
    });
});
