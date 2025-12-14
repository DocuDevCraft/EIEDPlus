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

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['can:isManager']], function () {
        Route::prefix('dashboard')->group(function () {
            Route::resource('freelancer', 'FreelancerController');
            Route::post('/freelancer/hourly-contract/send', 'FreelancerController@HourlyContractSubmit')->name('freelancer.hourly-contract.send');
            Route::post('/freelancer/destroy', 'FreelancerController@destroy')->name('freelancer.destroyed');
        });
    });
});
