<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AssociationController;
use App\Http\Controllers\TennisMatchController;

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

    // 🔐 Solo Admin puede modificar, eliminar asociaciones y registrar usuarios, jugadores y partidos
    Route::middleware(['is.admin'])->group(function () {
        // Usuarios
        Route::post('registro', [AuthController::class, 'register']);

        // Asociaciones
        Route::match(['put', 'patch'], 'asociaciones/{id}', [AssociationController::class, 'update']);
        Route::delete('asociaciones/{id}', [AssociationController::class, 'destroy']);

        // Jugadores
        Route::get('jugadores', [PlayerController::class, 'index']);
        Route::post('jugadores', [PlayerController::class, 'store']);
        Route::get('jugadores/{id}', [PlayerController::class, 'show']);
        Route::match(['put', 'patch'], 'jugadores/{id}', [PlayerController::class, 'update']);
        Route::delete('jugadores/{id}', [PlayerController::class, 'destroy']);

        // Partidos (tenis de mesa)
        Route::post('partidos', [TennisMatchController::class, 'store']);
        Route::match(['put', 'patch'], 'partidos/{id}', [TennisMatchController::class, 'update']);
    });

    // Ruta de prueba para testing
    if (app()->environment('testing')) {
        Route::middleware(['is.admin_or_arbitro'])->get('/api/prueba-rol', function () {
            return response()->json(['message' => 'Acceso permitido'], 200);
        });
    }
});
