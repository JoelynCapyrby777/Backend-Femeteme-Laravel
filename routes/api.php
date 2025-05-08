<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AssociationController;

// Rutas públicas
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware(['auth:api'])->group(function () {

    // 🔐 Rutas generales para cualquier usuario autenticado
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // 🔓 Todos los usuarios autenticados pueden ver asociaciones
    Route::get('asociaciones', [AssociationController::class, 'index']);

    // 🔐 Admin + Árbitro pueden crear y ver una asociación específica
    Route::middleware(['is.admin_or_arbitro'])->group(function () {
        Route::post('asociaciones', [AssociationController::class, 'store']);
        Route::get('asociaciones/{id}', [AssociationController::class, 'show']);
    });

    // 🔐 Solo Admin puede modificar, eliminar asociaciones y registrar usuarios y jugadores
    Route::middleware(['is.admin'])->group(function () {
        // Usuarios
        Route::post('registro', [AuthController::class, 'register']);

        // Asociaciones
        Route::patch('asociaciones/{id}', [AssociationController::class, 'update']);
        Route::delete('asociaciones/{id}', [AssociationController::class, 'destroy']);

        // Jugadores
        Route::get('jugadores', [PlayerController::class, 'obtenerJugadores']);
        Route::post('jugadores', [PlayerController::class, 'crearJugador']);
        Route::get('jugadores/{id}', [PlayerController::class, 'obtenerJugador']);
        Route::match(['put', 'patch'], 'jugadores/{id}', [PlayerController::class, 'modificarJugador']);
        Route::delete('jugadores/{id}', [PlayerController::class, 'eliminarJugador']);
    });

    // Ruta de prueba para testing
    if (app()->environment('testing')) {
        Route::middleware(['is.admin_or_arbitro'])->get('/api/prueba-rol', function () {
            return response()->json(['message' => 'Acceso permitido'], 200);
        });
    }
});
