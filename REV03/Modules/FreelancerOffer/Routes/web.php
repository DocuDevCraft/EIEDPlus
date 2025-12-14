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
            /* Work Package Offer */
            Route::get('freelancer-offer/check-access/{id}', 'FreelancerOfferController@checkAccess')->name('freelancer.offer.checkAccess');
            Route::post('freelancer-offer/send-otp/{id}', 'FreelancerOfferController@sendOTP')->name('freelancer.offer.sendOTP');
            Route::post('freelancer-offer/submit-otp/{id}', 'FreelancerOfferController@submitOTP')->name('freelancer.offer.submitOTP');

            Route::get('freelancer-offer/{id}', 'FreelancerOfferController@offerList')->name('freelancer.offer.list');
            Route::get('freelancer-offer-view/{id}', 'FreelancerOfferController@show')->name('freelancer-offer.edit');
            Route::put('freelancer-offer-view/{id}', 'FreelancerOfferController@update')->name('freelancer-offer.update');
            Route::put('offer-list-status/{id}', 'FreelancerOfferController@listStatus')->name('work-package-offer-status');
            Route::put('offer-sorting/{id}', 'FreelancerOfferController@sortUpdate')->name('work-package-offer-sorting');
            Route::post('offer-export-pdf/{id}', 'FreelancerOfferController@offerExportPDF')->name('work-package-offer-export-pdf');
            Route::post('offer-export-pdf-upload/{id}', 'FreelancerOfferController@offerExportPDFUpload')->name('work-package-offer-export-pdf-upload');
            Route::get('offer-pdf-download/{id}', 'FreelancerOfferController@offerPDFDownload')->name('work-package-offer-pdf-download');
            Route::get('offer-pdf-completed/{id}', 'FreelancerOfferController@offerListUploadCompleted')->name('work-package-offer-upload-completed');
        });
    });
});

