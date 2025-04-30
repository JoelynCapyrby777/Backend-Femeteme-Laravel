<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssociationController;
use App\Http\Middleware\CheckRole;

// Rutas públicas
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación básica
Route::middleware(['auth:api'])->group(function () {

    // Rutas básicas para usuarios autenticados
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // Rutas para todos los usuarios autenticados
    Route::get('associations', [AssociationController::class, 'obtenerAsociaciones']);

    // Rutas solo para Admin y Árbitro (roles 1 y 2)
    Route::middleware([CheckRole::class . ':1,2'])->group(function () {
        Route::post('associations', [AssociationController::class, 'crearAsociacion']);
        Route::get('associations/{id}', [AssociationController::class, 'obtenerAsociacion']);
        Route::patch('associations/{id}', [AssociationController::class, 'modificarAsociacion']);
        Route::delete('associations/{id}', [AssociationController::class, 'eliminarAsociacion']);
    });

    // Rutas exclusivas para Admin (rol 1)
    Route::middleware([CheckRole::class . ':1'])->group(function () {
        Route::post('register', [AuthController::class, 'register']);
    });
});
