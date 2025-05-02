<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsArbitro
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user || $user->role_id !== 2) {
            return response()->json([
                'message' => 'Acceso denegado',
                'error' => 'Solo árbitros pueden acceder a esta ruta.'
            ], 403);
        }

        return $next($request);
    }
}
