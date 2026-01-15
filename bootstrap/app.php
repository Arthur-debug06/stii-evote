<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/debug.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies for Railway deployment
        $middleware->trustProxies(at: '*');

        // Livewire file upload needs to be accessible
        $middleware->validateCsrfTokens(except: [
            'livewire/upload-file',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
