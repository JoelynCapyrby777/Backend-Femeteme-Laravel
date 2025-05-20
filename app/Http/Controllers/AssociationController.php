<?php

namespace App\Http\Controllers;

use App\Services\AssociationService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Association\AssociationResource;
use App\Http\Requests\Association\StoreAssociationRequest;
use App\Http\Requests\Association\UpdateAssociationRequest;

class AssociationController extends Controller
{
    protected AssociationService $service;

    public function __construct(AssociationService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $associations = $this->service->obtenerTodas();

        return response()->json([
            'data' => AssociationResource::collection($associations)
        ]);
    }

    public function show($id): JsonResponse
    {
        $association = $this->service->obtenerPorId($id);

        return response()->json([
            'data' => new AssociationResource($association)
        ]);
    }

    public function store(StoreAssociationRequest $request): JsonResponse
    {
        $association = $this->service->crear($request->validated());

        return response()->json([
            'message' => 'Asociación creada correctamente',
            'data'    => new AssociationResource($association)
        ], 201);
    }

    public function update(UpdateAssociationRequest $request, $id): JsonResponse
    {
        $association = $this->service->modificar($request->validated(), $id);

        return response()->json([
            'message' => 'La asociación se ha actualizado correctamente',
            'data'    => new AssociationResource($association)
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->service->eliminar($id);

        return response()->json(null, 204);
    }
}
