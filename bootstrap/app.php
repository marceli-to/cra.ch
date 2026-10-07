<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Sanctum's session first, so the throttle counts per user
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:200,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Dropzone posts uploads without the CSRF header
        $middleware->validateCsrfTokens(except: [
            'api/image/upload',
            'api/file/upload',
        ]);

        $middleware->redirectUsersTo(
            fn (Request $request) => $request->user()?->isAdmin() ? '/administration' : '/'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // The admin SPA expects JSON from every api/* route, 401 included
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );
    })->create();
