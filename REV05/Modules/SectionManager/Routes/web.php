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
			Route::post('/section/destroy', 'SectionManagerController@destroy')->name('section.multi.destroy');
            Route::resource('section', 'SectionManagerController');
            

            Route::post('/subsection/destroy', 'SubSectionController@destroy')->name('subsection.multi.destroy');
			Route::resource('subsection', 'SubSectionController');
            Route::post('/subsection/check/{value}', 'SubSectionController@Check');

			Route::post('/division/destroy', 'DivisionController@destroy')->name('division.multi.destroy');
            Route::resource('division', 'DivisionController');
            Route::post('/division/check/{value}', 'DivisionController@Check');
        });
    });
});
