<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PlayerService;
use Illuminate\Routing\Controller;

class PlayerController extends Controller
{
    protected $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
    }

    public function obtenerJugadores()
    {
        $result = $this->service->obtenerTodos();
        return response()->json($result['data'] ?? ['message' => $result['error']], $result['status']);
    }

    /**
     * Registrar un nuevo jugador
     * 
     * @bodyParam name string required Nombre completo del jugador. Example: Juan Pérez
     * @bodyParam email string required Correo electrónico único. Example: juan@example.com
     * @bodyParam password string required Contraseña segura. Example: secret123
     * @bodyParam password_confirmation string required Confirmación de contraseña. Example: secret123
     * @bodyParam curp string required CURP única del jugador. Example: CURPJUAN123456789
     * @bodyParam age integer required Edad del jugador. Example: 23
     * @bodyParam category string required Categoría del jugador (femenil o varonil). Example: varonil
     * @bodyParam association_id integer required ID de la asociación a la que pertenece. Example: 1
     */
    public function crearJugador(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'curp' => 'required|string|max:18|unique:players',
            'age' => 'required|integer|min:5|max:100',
            'category' => 'required|in:femenil,varonil',
            'association_id' => 'required|exists:associations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $this->service->crear($request->all());
        return response()->json($result['data'] ?? ['message' => $result['message'] ?? $result['error']], $result['status']);
    }

    public function obtenerJugador($id)
    {
        $result = $this->service->obtenerPorId($id);
        return response()->json($result['data'] ?? ['message' => $result['error']], $result['status']);
    }

    public function modificarJugador(Request $request, $id)
    {
        $result = $this->service->modificar($request->all(), $id);
        return response()->json(['message' => $result['message'] ?? $result['error']], $result['status']);
    }

    public function eliminarJugador($id)
    {
        $result = $this->service->eliminar($id);
        return response()->json(['message' => $result['message'] ?? $result['error']], $result['status']);
    }
}
