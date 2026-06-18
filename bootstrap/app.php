<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__."/../routes/console.php",
        health: "/up",
        using: function () {
            Route::middleware("api")
                ->prefix("api")
                ->group(base_path("routes/api.php"));
            Route::middleware("web")
                ->group(base_path("routes/web.php"));
            Route::middleware("web")
                ->prefix("admin")
                ->group(base_path("routes/admin.php"));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: "*");

        $middleware->encryptCookies(except: ["appearance", "sidebar_state"]);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            "role" => \Spatie\Permission\Middleware\RoleMiddleware::class,
            "permission" => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            "role_or_permission" => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
