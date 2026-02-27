<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\ExperienceController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->name('api.')->group(function () {
    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::prefix('profile-info')->name('profile-info.')->group(function () {
            Route::get('/', 'getProfileData')->name('get-profile');
            Route::post('/', 'store')->name('profile-info');
        });
        Route::prefix('main-page')->name('main-page.')->group(function () {
            Route::get('/', 'getData')->name('get');
            Route::post('', 'storeMainPage')->name('main-page-info');
        });

        Route::resource('skill', SkillController::class)->only([
            'index',
            'store'
        ]);

        Route::prefix('project')->name('project.')->controller(ProjectController::class)->group(function () {
            Route::post('/{project}', 'store')->name('update');
        });

        Route::resource('project', ProjectController::class)->only([
            'index',
            'show',
            'store',
            'destroy'
        ]);

        Route::resources([
            'testimonial' => TestimonialController::class,
            'service' => ServiceController::class,
            'experience' => ExperienceController::class,
            'education' => EducationController::class,
        ]);
    });
});

Route::prefix('auth')->controller(AuthController::class)->middleware(['verify.signature'])->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('generate/client-token', [TokenController::class, 'generateClientToken']);

Route::middleware('verify.signature')->group(function(){
    Route::prefix('pages')->middleware('throttle:global_api')->controller(PageController::class)->group(function () {
        Route::get('/home', 'homePageData')->name('home-page');
        Route::get('/services', 'servicesPageData')->name('services-page');
        Route::get('/testimonials', 'testimonialPageData')->name('services-page');
        Route::get('/contact', 'contactUsPage')->name('contact-page');
        Route::get('/about', 'AboutPage')->name('about-page');
    });
    Route::prefix('contact-us')->middleware('throttle:contact_form')->controller(ContactUsController::class)->group(function () {
        Route::post('/', 'store');
    });

});
