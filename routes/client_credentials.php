<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactUsController;
use Illuminate\Support\Facades\Route;

Route::prefix('contact-us')->controller(ContactUsController::class)->group(function(){
    Route::post('/', 'store');
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
});
