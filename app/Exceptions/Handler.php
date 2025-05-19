<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Excepciones personalizadas
use App\Exceptions\Association\AssociationNotFoundException;
use App\Exceptions\Association\AssociationValidationException;
use App\Exceptions\Association\AssociationConflictException;

use App\Exceptions\Player\PlayerNotFoundException;
use App\Exceptions\Player\PlayerValidationException;
use App\Exceptions\Player\PlayerConflictException;


class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        // Puedes dejar esto vacío si manejas las excepciones directamente en `render`
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            // Modelo o ruta no encontrada
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Recurso no encontrado.'], Response::HTTP_NOT_FOUND);
            }

            // No autenticado
            if ($e instanceof AuthenticationException) {
                return response()->json(['message' => 'No estás autenticado.'], Response::HTTP_UNAUTHORIZED);
            }

            // Validación de request
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Error de validación.',
                    'errors'  => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // --- Excepciones personalizadas ---

            // Asociación no encontrada
            if ($e instanceof AssociationNotFoundException) {
                return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
            }

            // Asociación: error de validación personalizada
            if ($e instanceof AssociationValidationException) {
                return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            }

            // Asociación: conflicto (duplicados)
            if ($e instanceof AssociationConflictException) {
                return response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
            }

            // Jugador no encontrado
            if ($e instanceof PlayerNotFoundException) {
                return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
            }

            // Jugador: error de validación personalizada
            
            // Asociación: conflicto (duplicados)



            // Error inesperado
            return response()->json([
                'message' => $e->getMessage() ?: 'Error interno del servidor.',
            ], method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return parent::render($request, $e);
    }
}
