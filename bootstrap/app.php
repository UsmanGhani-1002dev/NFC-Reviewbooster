<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\RedirectMiddleware::class);

        // Record public page views (skips admin/api/assets internally).
        $middleware->web(append: [
            \App\Http\Middleware\TrackPageView::class,
        ]);

        // Analytics beacons are fire-and-forget; exclude from CSRF.
        // The public chatbot endpoint is throttled and holds no secret client-side.
        $middleware->validateCsrfTokens(except: [
            'track/*',
            'chat',
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
