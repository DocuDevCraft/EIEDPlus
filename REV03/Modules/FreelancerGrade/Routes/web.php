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
        Route::prefix('freelancergrade')->group(function () {
            Route::get('/', 'FreelancerGradeController@index');
        });
    });
});
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['can:isManager']], function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/freelancer-grade', 'FreelancerGradeController@index')->name('freelancer-grade.index');
            Route::get('/freelancer-grade/{id}', 'FreelancerGradeController@edit')->name('freelancer-grade.edit');
            Route::put('/freelancer-grade/{id}', 'FreelancerGradeController@update')->name('freelancer-grade.update');
            Route::post('/freelancer-grade-delete', 'FreelancerGradeController@destroy')->name('freelancer-grade.destroy');
            Route::get('/freelancer-grade-history-details/{id}', 'FreelancerGradeController@historyDetails')->name('freelancer-grade.history.details');

            Route::post('/freelancer-grade-chat', 'FreelancerGradeChatController@store')->name('freelancer-grade-chat.store');
        });
    });
});
