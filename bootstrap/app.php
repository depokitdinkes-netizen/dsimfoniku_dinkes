<?php

use App\Http\Middleware\EnsureAdminAccessOwnData;
use App\Http\Middleware\EnsureRoleIsNotUser;
use App\Http\Middleware\EnsureRoleIsSuperadmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'not-user' => EnsureRoleIsNotUser::class,
            'superadmin' => EnsureRoleIsSuperadmin::class,
            'admin-own-data' => EnsureAdminAccessOwnData::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
