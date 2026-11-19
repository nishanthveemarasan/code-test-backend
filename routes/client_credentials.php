<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('contact-us')->controller(ContactUsController::class)->group(function(){
    Route::post('/', 'store');
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
});

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


