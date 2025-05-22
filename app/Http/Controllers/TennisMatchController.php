<?php

namespace App\Http\Controllers;

use App\Services\TennisMatchService;
use App\Http\Requests\TennisMatch\StoreTennisMatchRequest;
use App\Http\Resources\TennisMatch\TennisMatchResource;
use Illuminate\Http\JsonResponse;

class TennisMatchController extends Controller
{
    protected TennisMatchService $service;

    public function __construct(TennisMatchService $service)
    {
        $this->service = $service;
    }

    public function store(StoreTennisMatchRequest $request): JsonResponse
    {
        $match = $this->service->crear($request->validated());

        return response()->json([
            'message' => 'Partido registrado y ranking actualizado.',
            'data' => new TennisMatchResource($match),
        ], 201);
    }
}
