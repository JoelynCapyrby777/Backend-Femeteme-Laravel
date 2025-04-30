<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssociationService;

class AssociationController extends Controller
{
    protected $service;

    public function __construct(AssociationService $service)
    {
        $this->service = $service;
    }

    public function obtenerAsociaciones()
    {
        $result = $this->service->obtenerTodas();
        return response()->json($result['data'] ?? ['message' => $result['error']], $result['status']);
    }

    public function crearAsociacion(Request $request)
    {
        $result = $this->service->crear($request->all());
        return response()->json($result['data'] ?? ['message' => $result['message'] ?? $result['error']], $result['status']);
    }

    public function obtenerAsociacion($id)
    {
        $result = $this->service->obtenerPorId($id);
        return response()->json($result['data'] ?? ['message' => $result['error']], $result['status']);
    }

    public function modificarAsociacion(Request $request, $id)
    {
        $result = $this->service->modificar($request->all(), $id);
        return response()->json(['message' => $result['message'] ?? $result['error']], $result['status']);
    }

    public function eliminarAsociacion($id)
    {
        $result = $this->service->eliminar($id);
        return response()->json(['message' => $result['message'] ?? $result['error']], $result['status']);
    }
}
