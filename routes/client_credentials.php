<?php

use Illuminate\Support\Facades\Route;
Route::middleware(['client', 'client.owner'])->group(function () {
    Route::get('/test', function () {
        // This logic only runs if the token belongs to the 
        // client that created this specific resource.
        dd('coming here');
    });
});