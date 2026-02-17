<?php

use App\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (Application $app): void {
            Route::prefix('api/client')
                    ->middleware([
                        'client:client_authentication',
                        ForceJsonResponseMiddleware::class
                    ])
                    ->group(base_path('routes/client_credentials.php'));

            Route::prefix('api/oauth')
                ->group(__DIR__.'/../routes/passport.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append:[
            ForceJsonResponseMiddleware::class
        ]);
        $middleware->alias([
            'client' => \Laravel\Passport\Http\Middleware\CheckToken::class,
            'client.owner' => \Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
