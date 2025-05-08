<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

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
        //
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            // Modelo o ruta no encontrada
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Recurso no encontrado.'], Response::HTTP_NOT_FOUND);
            }

            // Association not found
            if ($e instanceof AssociationNotFoundException) {
                return response()->json(['message' => 'Asociación no encontrada.'], Response::HTTP_NOT_FOUND);
            }

            // Player not found
            if ($e instanceof PlayerNotFoundException) {
                return response()->json(['message' => 'Jugador no encontrado.'], Response::HTTP_NOT_FOUND);
            }

            // No autenticado
            if ($e instanceof AuthenticationException) {
                return response()->json(['message' => 'No estás autenticado.'], Response::HTTP_UNAUTHORIZED);
            }

            // Validación
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Error de validación.',
                    'errors'  => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Cualquier otra excepción
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
