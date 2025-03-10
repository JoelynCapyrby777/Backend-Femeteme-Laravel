<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssociationController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

// Rutas públicas
Route::post('login', [AuthController::class, 'login']); // Login público

// Rutas privadas (requiere autenticación)
Route::middleware([CheckRole::class])->group(function () {

    // Rutas para la autenticación del usuario
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout'); // Solo usuarios autenticados
        Route::get('me', 'obtenerUsuario'); // Solo usuarios autenticados
    });

    // Rutas accesibles por cualquier usuario autenticado (pueden incluir roles específicos si es necesario)
    Route::get('associations', [AssociationController::class, 'obtenerAsociaciones']);  // Accesible para todos los usuarios autenticados

    // Rutas accesibles solo para Admin y Arbitro (roles 1 y 2)
    Route::middleware([CheckRole::class . ':1,2'])->group(function () {
        Route::controller(AssociationController::class)->group(function () {
            Route::post('associations', 'crearAsociacion'); // Solo Admin y Arbitro pueden crear asociaciones
            Route::get('associations/{id}', 'obtenerAsociacion'); // Solo Admin y Arbitro pueden ver asociaciones
            Route::patch('associations/{id}', 'modificarAsociacion'); // Solo Admin y Arbitro pueden modificar asociaciones
            Route::delete('associations/{id}', 'eliminarAsociacion'); // Solo Admin y Arbitro pueden eliminar asociaciones
        });
    });

    // Rutas exclusivas para Admin (rol 1)
    Route::middleware([CheckRole::class . ':1'])->group(function () {
        // Solo Admin puede registrar nuevas asociaciones
        Route::post('register', [AuthController::class, 'register']); // Registro solo accesible para Admin
    });
});
