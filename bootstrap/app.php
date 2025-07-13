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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            guests: '/',    // Unauthenticated users go here
            users: '/dashboard'  // Authenticated users trying to access guest routes go here
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
