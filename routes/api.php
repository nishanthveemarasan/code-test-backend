<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});#

Route::apiResource('products', ProductController::class);

