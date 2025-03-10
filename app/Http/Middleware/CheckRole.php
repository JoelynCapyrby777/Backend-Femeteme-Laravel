<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja una solicitud entrante y verifica los roles del usuario.
     *
     * @param  \Illuminate\Http\Request  $request  La solicitud entrante.
     * @param  \Closure  $next  La siguiente acción en la cadena de middleware.
     * @param  mixed  ...$roles  Lista de roles permitidos para acceder a la ruta.
     * @return \Symfony\Component\HttpFoundation\Response Respuesta HTTP.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth('api')->user();

        // 🔴 Si el usuario no está autenticado
        if (!$user) {
            return response()->json([
                'message' => 'No autenticado',
                'error' => 'Debes iniciar sesión para acceder a este recurso.'
            ], 401);
        }

        // 🔴 Verificar si el usuario tiene acceso basado en su role_id
        if (!in_array($user->role_id, $roles)) {
            return response()->json([
                'message' => 'Acceso denegado',
                'error' => $this->getErrorMessage($roles),
                'tu_rol' => $this->getRoleName($user->role_id),
                'roles_permitidos' => array_map(fn($role) => $this->getRoleName($role), $roles),
            ], 403);
        }

        return $next($request);
    }

    /**
     * Devuelve un mensaje de error según los roles requeridos.
     *
     * @param  array  $roles  Lista de roles permitidos.
     * @return string Mensaje de error personalizado.
     */
    private function getErrorMessage($roles): string
    {
        if ($roles === [1]) {
            return 'Solo los administradores pueden acceder a esta función.';
        }
        if ($roles === [1, 2]) {
            return 'Solo administradores y árbitros tienen acceso.';
        }
        return 'No tienes permisos para acceder a esta ruta.';
    }

    /**
     * Devuelve el nombre del rol según su ID.
     *
     * @param  int  $role_id  ID del rol.
     * @return string Nombre del rol.
     */
    private function getRoleName(int $role_id): string
    {
        return match ($role_id) {
            1 => 'Admin',
            2 => 'Arbitro',
            3 => 'Jugador',
            default => 'Desconocido',
        };
    }
}
