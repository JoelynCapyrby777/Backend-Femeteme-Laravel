<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminOrArbitro
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user || !in_array($user->role_id, [1, 2])) {
            return response()->json([
                'message' => 'Acceso denegado',
                'error' => 'Solo administradores o árbitros pueden acceder a esta ruta.'
            ], 403);
        }

        return $next($request);
    }
}
