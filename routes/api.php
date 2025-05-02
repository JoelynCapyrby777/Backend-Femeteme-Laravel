<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssociationController;

// Rutas públicas
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware(['auth:api'])->group(function () {

    // Rutas generales para cualquier usuario autenticado
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // 🔓 Todos los usuarios autenticados pueden ver asociaciones (incl. Admin, Árbitro y Jugador)
    Route::get('associations', [AssociationController::class, 'obtenerAsociaciones']);

    // 🔐 Admin + Árbitro pueden crear y ver una asociación específica
    Route::middleware(['is.admin_or_arbitro'])->group(function () {
        Route::post('associations', [AssociationController::class, 'crearAsociacion']);
        Route::get('associations/{id}', [AssociationController::class, 'obtenerAsociacion']);
    });

    // 🔐 Solo Admin puede modificar, eliminar asociaciones y registrar usuarios
    Route::middleware(['is.admin'])->group(function () {
        Route::patch('associations/{id}', [AssociationController::class, 'modificarAsociacion']);
        Route::delete('associations/{id}', [AssociationController::class, 'eliminarAsociacion']);
        Route::post('register', [AuthController::class, 'register']);
    });

    // Ruta de prueba (solo para testing)
    if (app()->environment('testing')) {
        Route::middleware(['is.admin_or_arbitro'])->get('/api/prueba-rol', function () {
            return response()->json(['message' => 'Acceso permitido'], 200);
        });
    }
});
