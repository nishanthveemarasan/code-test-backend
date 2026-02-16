<?php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Passport;

Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'], function () {
    Route::post('/token', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'token',
        'middleware' => 'throttle',
    ]);
    
    Route::get('/authorize', [
        'uses' => 'AuthorizationController@authorize',
        'as' => 'authorizations.authorize',
        'middleware' => 'web',
    ]);
});

$guard = config('passport.guard', null);

Route::middleware(['web', $guard ? 'auth:'.$guard : 'auth'])->group(function () {
    // Route::post('/token/refresh', [
    //     'uses' => 'TransientTokenController@refresh',
    //     'as' => 'token.refresh',
    // ]);
    Route::post('/authorize', [ApproveAuthorizationController::class, 'approve'])
        ->name('passport.authorizations.approve');

    Route::delete('/authorize', [DenyAuthorizationController::class, 'deny'])
        ->name('passport.authorizations.deny');

    // Token Management
    Route::get('/tokens', [AuthorizedAccessTokenController::class, 'forUser'])
        ->name('passport.tokens.index');

    Route::delete('/tokens/{token_id}', [AuthorizedAccessTokenController::class, 'destroy'])
        ->name('passport.tokens.destroy');

    // Route::post('/authorize', [
    //     'uses' => 'ApproveAuthorizationController@approve',
    //     'as' => 'authorizations.approve',
    // ]);

    // Route::delete('/authorize', [
    //     'uses' => 'DenyAuthorizationController@deny',
    //     'as' => 'authorizations.deny',
    // ]);

    // Route::get('/tokens', [
    //     'uses' => 'AuthorizedAccessTokenController@forUser',
    //     'as' => 'tokens.index',
    // ]);

    // Route::delete('/tokens/{token_id}', [
    //     'uses' => 'AuthorizedAccessTokenController@destroy',
    //     'as' => 'tokens.destroy',
    // ]);

    // Route::get('/clients', [
    //     'uses' => 'ClientController@forUser',
    //     'as' => 'clients.index',
    // ]);

    // Route::post('/clients', [
    //     'uses' => 'ClientController@store',
    //     'as' => 'clients.store',
    // ]);

    // Route::put('/clients/{client_id}', [
    //     'uses' => 'ClientController@update',
    //     'as' => 'clients.update',
    // ]);

    // Route::delete('/clients/{client_id}', [
    //     'uses' => 'ClientController@destroy',
    //     'as' => 'clients.destroy',
    // ]);

    // Route::get('/scopes', [
    //     'uses' => 'ScopeController@all',
    //     'as' => 'scopes.index',
    // ]);

    // Route::get('/personal-access-tokens', [
    //     'uses' => 'PersonalAccessTokenController@forUser',
    //     'as' => 'personal.tokens.index',
    // ]);

    // Route::post('/personal-access-tokens', [
    //     'uses' => 'PersonalAccessTokenController@store',
    //     'as' => 'personal.tokens.store',
    // ]);

    // Route::delete('/personal-access-tokens/{token_id}', [
    //     'uses' => 'PersonalAccessTokenController@destroy',
    //     'as' => 'personal.tokens.destroy',
    // ]);
});
