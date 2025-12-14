<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('work-package/signature-digest-action/{id}', 'WorkPackageManagerAPIController@workPackageSignatureDigestAction');

Route::get('/', function () {
    return redirect("/login");
})->name('home');


Route::get('/freelancer/{count}', function ($count) {
    $Users = \Modules\Users\Entities\Users::factory()->count($count)->create()->each(function ($query) {
        $query->freelancer()->save(\Modules\Freelancer\Entities\Freelancer::factory()->make());
        $query->education()->saveMany(\Modules\Freelancer\Entities\FreelancerEducation::factory()->count(3)->make());
        $query->education()->saveMany(\Modules\Freelancer\Entities\FreelancerCourses::factory()->count(3)->make());
        $query->education()->saveMany(\Modules\Freelancer\Entities\FreelancerLanguage::factory()->count(2)->make());
        $query->education()->saveMany(\Modules\Freelancer\Entities\FreelancerWorkExperience::factory()->count(3)->make());
    })->toArray();

    foreach ($Users as $item) {
        $item['password'] = '12345678';
        print_r($item);
    }
});

Route::get('/user/{count}', function ($count) {
    $Users = \Modules\Users\Entities\Users::factory()->count($count)->create()->toArray();
    foreach ($Users as $item) {
        $item['password'] = '12345678';
        print_r($item);
    }
});

Route::get('/email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

/* CKEditor Image Upload */
Route::group(['middleware' => ['auth']], function () {
    Route::post('dashboard/ckeditor/upload/{path}', [App\Http\Controllers\CKEditorController::class, 'upload'])->name('ckeditor.image-upload');
});

/* Clear Cache */
Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    \Cookie::queue(\Cookie::forget('userData'));
    \Cookie::queue(\Cookie::forget('eied_session'));
    \Cookie::queue(\Cookie::forget('XSRF-TOKEN'));
    \Cookie::queue(\Cookie::forget('auth_token'));

    return 'DONE';
});

Auth::routes();
