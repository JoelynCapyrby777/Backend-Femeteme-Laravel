<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:10|max:100',
            'email' => 'required|string|email|min:10|max:50|unique:users',
            'password' => 'required|string|min:10|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = $this->authService->register($request->all());
        return response()->json(['message' => 'Usuario creado exitosamente', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|min:10|max:50',
            'password' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $token = $this->authService->login($request->only('email', 'password'));

        if (!$token) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    public function logout()
    {
        $exito = $this->authService->logout();

        if (!$exito) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], 500);
        }

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    public function me()
    {
        $user = $this->authService->me();

        if (!$user) {
            return response()->json(['error' => 'Token inválido o expirado'], 401);
        }

        return response()->json($user);
    }

    public function refresh()
    {
        $newToken = $this->authService->refresh();

        if (!$newToken) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        }

        return response()->json(['token' => $newToken]);
    }
}
