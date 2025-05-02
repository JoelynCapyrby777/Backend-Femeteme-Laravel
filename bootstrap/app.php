<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsArbitro;
use App\Http\Middleware\IsJugador;
use App\Http\Middleware\IsAdminOrArbitro;
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

            'check.role' => CheckRole::class,
            'is.admin' => IsAdmin::class,
            'is.arbitro' => IsArbitro::class,
            'is.jugador' => IsJugador::class,
            'is.admin_or_arbitro' => IsAdminOrArbitro::class,

        ]);
    })    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
