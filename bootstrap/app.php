<?php

use App\Helper\ApiResponse;
use App\Http\Middleware\ForceJsonResponseMiddleware;
use App\Http\Middleware\VerifySingatureMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (Application $app): void {
            Route::prefix('api/client')
                    ->middleware([
                        'api',
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
            'verify.signature' => VerifySingatureMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                Log::channel('exception')->warning('Rate Limit Exceeded', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
                return ApiResponse::error(
                    'Rate limit exceeded. Please wait a moment before trying again.', 
                    ['retry_after' => $e->getHeaders()['Retry-After'] ?? null], 
                    429
                );
            }
        });
    })->create();
