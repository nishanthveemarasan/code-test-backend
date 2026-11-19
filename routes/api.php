<?php

use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\ExperienceController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\SkillController;
use App\Http\Controllers\API\TestimonialController;
use App\Http\Controllers\API\UserController;
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
        ])->names([
            'index' => 'skills.index',
            'store' => 'skills.store',
        ]);

        Route::resource('project', ProjectController::class)->only([
            'index',
            'show',
            'store',
            'destroy'
        ])->names([
                'index' => 'projects.index',
                'show' => 'projects.show',
                'store' => 'projects.store',
                'destroy' => 'projects.destroy',
            ]);

        Route::resources([
            'testimonial' => TestimonialController::class,
            'service' => ServiceController::class,
            'experience' => ExperienceController::class,
            'education' => EducationController::class,
        ]);
    });
});

