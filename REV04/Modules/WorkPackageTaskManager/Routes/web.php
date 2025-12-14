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
            /* Work Package Task Manager */
            Route::get('work-package-task-manager/{id}', 'WorkPackageTaskManagerController@show')->name('work-package-task-manager.blocks');
            Route::post('work-package-task-manager/{id}', 'WorkPackageTaskManagerController@store')->name('work-package-task-manager.store');
            Route::post('work-package-task-manager/export/{id}', 'WorkPackageTaskManagerController@exportActivity')->name('work-package-task-manager.export');
            Route::post('work-package-task-manager/import/{id}', 'WorkPackageTaskManagerController@importActivity')->name('work-package-task-manager.import');

            /* Work Package Task Chat */
            Route::get('work-package-task-comment/{id}', 'TaskChatController@taskComment')->name('task-comment.get');
            Route::post('work-package-task-comment/{id}', 'TaskChatController@taskCommentStore')->name('task-comment.store');
        });
    });
});
