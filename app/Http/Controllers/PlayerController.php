<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlayerService;

class PlayerController extends Controller
{
    protected $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:api', 'is.admin']);
    }

    public function index()
    {
        $result = $this->service->list();
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $result = $this->service->create($request->all());
        return response()->json($result['data'] ?? ['message' => $result['error']], $result['status']);
    }

    public function show($id)
    {
        $result = $this->service->find($id);
        return response()->json($result['data'] ?? ['message' => $result['error']], $result['status']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'curp' => "sometimes|string|max:18|unique:players,curp,{$id}",
            'age' => 'sometimes|integer|min:5|max:100',
            'category' => 'sometimes|in:femenil,varonil',
            'ranking_position' => 'nullable|integer|min:1',
            'association_id' => 'sometimes|exists:associations,id',
        ]);

        $player = $this->service->update($id, $data);
        return response()->json($player);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
