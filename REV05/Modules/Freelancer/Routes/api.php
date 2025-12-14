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

/* Identity Information */
Route::get('my-account/identity-information', 'IdentityInformationController@get')->middleware('auth:sanctum');
Route::post('my-account/identity-information', 'IdentityInformationController@identity')->middleware('auth:sanctum');
Route::post('my-account/additional-information', 'IdentityInformationController@additional_information')->middleware('auth:sanctum');

/* Account Authentication */
Route::get('my-account/account-authentication', 'AccountAuthenticationController@userCheck')->middleware('auth:sanctum');
Route::get('my-account/check-certificate', 'AccountAuthenticationController@checkCertificate')->middleware('auth:sanctum');
//Route::get('my-account/authentication-check', 'AccountAuthenticationController@AuthCheck')->middleware('auth:sanctum');
Route::post('my-account/set-auth', 'AccountAuthenticationController@setAuth')->middleware('auth:sanctum');


/* Education Information */
Route::get('my-account/education-information', 'EducationInformationController@get')->middleware('auth:sanctum');
Route::post('my-account/education-information', 'EducationInformationController@store')->middleware('auth:sanctum');
Route::put('my-account/education-information', 'EducationInformationController@update')->middleware('auth:sanctum');
Route::delete('my-account/education-information', 'EducationInformationController@delete')->middleware('auth:sanctum');

/* Work Experience History */
Route::get('my-account/work-experience-history', 'WorkExperienceHistoryController@get')->middleware('auth:sanctum');
Route::post('my-account/work-experience-history', 'WorkExperienceHistoryController@store')->middleware('auth:sanctum');
Route::put('my-account/work-experience-history', 'WorkExperienceHistoryController@update')->middleware('auth:sanctum');
Route::delete('my-account/work-experience-history', 'WorkExperienceHistoryController@delete')->middleware('auth:sanctum');

/* Course History */
Route::get('my-account/course-history', 'CourseHistoryController@get')->middleware('auth:sanctum');
Route::post('my-account/course-history', 'CourseHistoryController@store')->middleware('auth:sanctum');
Route::put('my-account/course-history', 'CourseHistoryController@update')->middleware('auth:sanctum');
Route::delete('my-account/course-history', 'CourseHistoryController@delete')->middleware('auth:sanctum');

/* Language History */
Route::get('my-account/language-history', 'LanguageHistoryController@get')->middleware('auth:sanctum');
Route::post('my-account/language-history', 'LanguageHistoryController@store')->middleware('auth:sanctum');
Route::put('my-account/language-history', 'LanguageHistoryController@update')->middleware('auth:sanctum');
Route::delete('my-account/language-history', 'LanguageHistoryController@delete')->middleware('auth:sanctum');

/* Speciality */
Route::get('my-account/speciality', 'SpecialityController@get')->middleware('auth:sanctum');
Route::post('my-account/speciality', 'SpecialityController@store')->middleware('auth:sanctum');
Route::delete('my-account/speciality', 'SpecialityController@delete')->middleware('auth:sanctum');

/* Financial Information */
Route::get('my-account/financial-information', 'FinancialController@get')->middleware('auth:sanctum');
Route::put('my-account/financial-information', 'FinancialController@update')->middleware('auth:sanctum');

/* Get Freelancer Special List */
Route::get('my-account/get-speciality-list/{count}', 'SpecialityController@getList')->middleware('auth:sanctum');

/* Agreement */
Route::get('my-account/accept-rules', 'AcceptRulesController@get')->middleware('auth:sanctum');
Route::post('my-account/accept-rules-signature-request', 'AcceptRulesController@acceptRulesContractSignatureRequest')->middleware('auth:sanctum');
Route::post('my-account/accept-rules', 'AcceptRulesController@acceptRules')->middleware('auth:sanctum');
//Route::post('my-account/accept-rules', 'AcceptRulesController@update')->middleware('auth:sanctum');

Route::get('my-account/hourly-contract', 'HourlyContractController@get')->middleware('auth:sanctum');
Route::post('my-account/hourly-contract-signature-request', 'HourlyContractController@HourlyContractSignatureRequest')->middleware('auth:sanctum');
Route::post('my-account/hourly-contract', 'HourlyContractController@HourlyContract')->middleware('auth:sanctum');
