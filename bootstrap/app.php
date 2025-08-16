<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'publisher' => \App\Http\Middleware\PublisherMiddleware::class,
            'publisher.validated' => \App\Http\Middleware\CheckPublisherValidation::class,
        ]);

        // Global middleware
        $middleware->web([
            \App\Http\Middleware\ShareWebsiteSettings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
