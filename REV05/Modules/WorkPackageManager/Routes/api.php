<?php

use Illuminate\Http\Request;

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

/* Work Package Route */
Route::get('work-package/list/{slug}/{count}', 'WorkPackageManagerAPIController@workPackageList')->middleware('auth:sanctum');
Route::get('work-package/details/{id}', 'WorkPackageManagerAPIController@workPackageDetails')->middleware('auth:sanctum');

/* Work Package Signature */
Route::get('work-package/public/chat/{id}', 'WorkPackageChatAPIController@chatList')->middleware('auth:sanctum');
Route::post('work-package/public/chat/{id}', 'WorkPackageChatAPIController@chatSubmit')->middleware('auth:sanctum');

/* Work Package Signature */
Route::get('work-package/signature/{id}', 'WorkPackageManagerAPIController@workPackageSignature')->middleware('auth:sanctum');
Route::post('work-package/signature/{id}', 'WorkPackageManagerAPIController@workPackageSignatureStore')->middleware('auth:sanctum');
Route::post('work-package/signature-auth/{id}', 'WorkPackageManagerAPIController@workPackageSignatureAuthStore')->middleware('auth:sanctum');
Route::post('work-package/signature-request/{id}', 'WorkPackageManagerAPIController@workPackageSignatureRequest')->middleware('auth:sanctum');
Route::post('work-package/signature-digest-action/{id}', 'WorkPackageManagerAPIController@workPackageSignatureDigestAction')->middleware('auth:sanctum');
Route::post('work-package/signature-process-action/{id}', 'WorkPackageManagerAPIController@workPackageSignatureProcessAction')->middleware('auth:sanctum');
