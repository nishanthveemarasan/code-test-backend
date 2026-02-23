<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\ExperienceController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->name('api.')->group(function () {
   Route::prefix('user')->name('user.')->controller(UserController::class)->group(function(){
        Route::post('contact-info', 'store')->name('contact-info');
        Route::prefix('experience')->name('experience.')->controller(ExperienceController::class)->group(function(){
            Route::post('/', 'storeExperience')->name('experience.store');
            Route::patch('/{experience}', 'updateExperience')->name('experience.update');
            Route::delete('/{experience}', 'deleteExperience')->name('experience.delete');
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

