<?php

use App\Http\Controllers\API\ContactUsController;
use Illuminate\Support\Facades\Route;
Route::middleware(['client', 'client.owner'])->group(function () {
    Route::get('/test', function () {
        // This logic only runs if the token belongs to the 
        // client that created this specific resource.
        dd('coming here');
    });
    Route::prefix('contact-us')->controller(ContactUsController::class)->group(function(){
        Route::post('/', 'store');
    });
});