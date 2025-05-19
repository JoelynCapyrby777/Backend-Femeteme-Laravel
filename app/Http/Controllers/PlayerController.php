<?php

namespace App\Http\Controllers;

use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Requests\Player\StorePlayerRequest;
use App\Http\Requests\Player\UpdatePlayerRequest;

class PlayerController extends Controller
{
    protected PlayerService $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $players = $this->service->obtenerTodos();
        
        return response()->json([
        'data' => PlayerResource::collection($players)
    ]);
    }

    public function show($id): JsonResponse
    {
        $player = $this->service->obtenerPorId($id);
        
        return response()->json([
        'data' => new PlayerResource($player)
    ]);
    }

    public function store(StorePlayerRequest $request): JsonResponse
    {
        $player = $this->service->crear($request->validated());

        return response()->json([
            'message' => 'Jugador creado correctamente',
            'data'    => new PlayerResource($player),
        ], 201);
    }

    public function update(UpdatePlayerRequest $request, $id): JsonResponse
    {
        $player = $this->service->modificar($request->validated(), $id);

        return response()->json([
            'message' => 'Jugador actualizado correctamente',
            'data'    => new PlayerResource($player),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->service->eliminar($id);

        return response()->json(null, 204);
    }
}
