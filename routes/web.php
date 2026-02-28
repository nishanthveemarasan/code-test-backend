<?php

use App\Http\Controllers\DropboxController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dropbox')->group(function(){

    Route::get('/authorize/app/{event}',[DropboxController::class, 'authorizeDropboxApp']);
    Route::get('/authorize/code/{event}',[DropboxController::class, 'dropboxAuthorizeCode']);
});