<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Spatie\Permission\Middleware\{RoleMiddleware, PermissionMiddleware, RoleOrPermissionMiddleware};
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })

    ->withCommands([
        \App\Console\Commands\MakeDtoCommand::class,
        \App\Console\Commands\MakeServiceCommand::class,
        \App\Console\Commands\MakeDomainCommand::class,
    ])

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
