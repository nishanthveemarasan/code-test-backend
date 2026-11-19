<?php

use App\Http\Controllers\API\ContactUsController;
use Illuminate\Support\Facades\Route;

Route::prefix('contact-us')->controller(ContactUsController::class)->group(function(){
    Route::post('/', 'store');
});
