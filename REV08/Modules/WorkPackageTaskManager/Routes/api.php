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


/* Work Package Task */
Route::get('work-package/show/{id}', 'WorkPackageTaskManagerAPIController@show')->middleware('auth:sanctum');
Route::get('work-package/task-show/{id}', 'WorkPackageTaskManagerAPIController@taskShow')->middleware('auth:sanctum');
Route::get('work-package/task-details/{id}', 'WorkPackageTaskManagerAPIController@taskDetails')->middleware('auth:sanctum');

/* Work Package Chat */
Route::get('work-package/chat/{id}', 'WorkPackageChatAPIController@get')->middleware('auth:sanctum');
Route::post('work-package/chat/{id}', 'WorkPackageChatAPIController@store')->middleware('auth:sanctum');

/* Task Chat */
Route::get('work-package/task/chat/{id}', 'TaskChatAPIController@get')->middleware('auth:sanctum');
Route::post('work-package/task/chat/{id}', 'TaskChatAPIController@store')->middleware('auth:sanctum');

/* Task Progress */
Route::get('work-package/task/progress/{id}', 'WorkPackageTaskManagerAPIController@progressList')->middleware('auth:sanctum');
Route::post('work-package/task/progress/{id}', 'WorkPackageTaskManagerAPIController@progressStore')->middleware('auth:sanctum');
