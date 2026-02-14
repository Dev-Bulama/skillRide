<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'gps' => \App\Http\Middleware\EnsureGpsEnabled::class,
            'audit' => \App\Http\Middleware\AuditActivity::class,
            'single.session' => \App\Http\Middleware\SingleSession::class,
            'impersonate' => \App\Http\Middleware\AdminImpersonate::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SingleSession::class,
            \App\Http\Middleware\AdminImpersonate::class,
        ]);

        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
