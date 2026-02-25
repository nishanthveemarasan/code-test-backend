<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\ExperienceController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->name('api.')->group(function () {
   Route::prefix('user')->name('user.')->controller(UserController::class)->group(function(){
        Route::prefix('profile-info')->name('profile-info.')->group(function(){
            Route::get('/', 'getProfileData')->name('get-profile');
            Route::post('/', 'store')->name('profile-info');
        });
        Route::prefix('main-page')->name('main-page.')->group(function(){
            Route::get('/', 'getData')->name('get');
            Route::post('', 'storeMainPage')->name('main-page-info');

        });
        Route::prefix('experience')->name('experience.')->controller(ExperienceController::class)->group(function(){
            Route::get('/', 'list')->name('list');
            Route::post('/', 'store')->name('store');
            Route::patch('/{experience}', 'update')->name('update');
            Route::delete('/{experience}', 'delete')->name('delete');
        });
        Route::prefix('education')->name('education.')->controller(EducationController::class)->group(function () {
            Route::get('/', 'list')->name('list');
            Route::post('/', 'store')->name('store');
            Route::patch('{education}', 'update')->name('update');
            Route::delete('{education}', 'destroy')->name('destroy');
        });
        Route::prefix('skill')->name('skill.')->controller(SkillController::class)->group(function () {
            Route::post('/', 'store')->name('store');
        });
        Route::prefix('testimonial')->name('testimonial.')->controller(TestimonialController::class)->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('{testimonial}', 'update')->name('update');
            Route::delete('{testimonial}', 'delete')->name('delete');
        });
        Route::prefix('service')->name('service.')->controller(ServiceController::class)->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('{service}', 'update')->name('update');
            Route::delete('{service}', 'delete')->name('delete');
        });
        Route::prefix('project')->name('project.')->controller(ProjectController::class)->group(function () {
            Route::post('/', 'save')->name('store');
            Route::post('/{project}', 'save')->name('update');
            Route::delete('/{project}', 'delete')->name('delete');
        });
    
   });
});

Route::prefix('auth')->controller(AuthController::class)->middleware(['verify.signature'])->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('generate/client-token', [TokenController::class, 'generateClientToken']);
Route::prefix('contact-us')->middleware(['verify.signature', 'throttle:contact_form'])->controller(ContactUsController::class)->group(function(){
    Route::post('/', 'store');
});

