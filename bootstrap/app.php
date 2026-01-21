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
        // Enregistrer le middleware de modération
        $middleware->alias([
            'can_moderate' => \App\Http\Middleware\CanModerate::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'verified.admin' => \App\Http\Middleware\EnsureEmailIsVerifiedForAdmins::class,
        ]);

        // Appliquer le middleware de vérification email pour les admins sur les routes admin
        $middleware->group('admin', [
            'verified.admin',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
