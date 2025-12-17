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
            /* Work Package Manager */
            Route::put('work-package-status/{id}', 'WorkPackageManagerController@updateStatus')->name('work-package.status');
            Route::resource('work-package', 'WorkPackageManagerController');
            Route::get('work-package/block/{id}', 'BlockWorkPackageController@BlockManagement');
            //Route::post('work-package/destroy', 'WorkPackageManagerController@destroy')->name('work-package.destroy');

            /* Work Package Public Chat */
            Route::get('work-package-chat/{id}', 'WorkPackageChatController@show');
            Route::post('work-package-chat/{id}', 'WorkPackageChatController@store')->name('work-package-chat.store');
            Route::post('/work-package-chat/destroy', 'WorkPackageChatController@destroy')->name('work-package-chat.destroy');

            /* Work Package Manager Chat */
            Route::get('work-package-manager-chat/{id}', 'WorkPackageManagerChatController@show');
            Route::post('work-package-manager-chat/{id}', 'WorkPackageManagerChatController@store')->name('work-package-manager-chat.store');
            Route::post('/work-package-manager-chat/destroy', 'WorkPackageManagerChatController@destroy')->name('work-package-manager-chat.destroy');

            /* Work Package Task */
            Route::get('work-package-task/{id}', 'WorkPackageProgressController@show');
            Route::put('work-package-task/{id}', 'WorkPackageProgressController@taskUpdate')->name('task.update');

            /* Work Package Task Progress */
            Route::get('work-package-task-progress/{id}', 'WorkPackageProgressController@taskShow')->name('task.show');
            Route::put('work-package-task-progress/{id}', 'WorkPackageProgressController@progressUpdate')->name('progress.update');

            /* Work Package Freelancer Grade */
            Route::get('work-package-freelancer-grade/edit/{id}', 'WorkPackageFreelancerGradeController@WorkPackageFreelancerGradeEdit')->name('WorkPackageFreelancerGrade.edit');
            Route::post('work-package-freelancer-grade/{id}', 'WorkPackageFreelancerGradeController@WorkPackageFreelancerGradeUpdate')->name('WorkPackageFreelancerGrade.update');
            Route::get('work-package-freelancer-grade-details/{id}', 'WorkPackageFreelancerGradeController@WorkPackageFreelancerGradeDetails')->name('WorkPackageFreelancerGrade.details');
        });

    });
});
