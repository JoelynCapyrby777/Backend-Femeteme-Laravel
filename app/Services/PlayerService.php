<?php

namespace App\Services;

use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PlayerService
{
    public function list()
    {
        return Player::with(['user:id,name,email', 'association:id,name'])->get();
    }

    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'curp' => 'required|string|max:18|unique:players',
            'age' => 'required|integer|min:5|max:100',
            'category' => 'required|in:femenil,varonil',
            'ranking_position' => 'nullable|integer|min:1',
            'association_id' => 'required|exists:associations,id',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors(), 'status' => 422];
        }

        // Crear el usuario con rol jugador (role_id = 3)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 3, // Rol jugador
        ]);

        // Crear el jugador asociado al usuario
        $player = Player::create([
            'user_id' => $user->id,
            'curp' => $data['curp'],
            'age' => $data['age'],
            'category' => $data['category'],
            'ranking_position' => $data['ranking_position'] ?? null,
            'association_id' => $data['association_id'],
        ]);

        return ['data' => $player->load('user', 'association'), 'status' => 201];
    }

    public function find($id)
    {
        $player = Player::with(['user:id,name,email', 'association:id,name'])->find($id);

        if (!$player) {
            return ['error' => 'Jugador no encontrado', 'status' => 404];
        }

        return ['data' => $player, 'status' => 200];
    }

    public function update($id, array $data)
    {
        $player = Player::findOrFail($id);
        $player->update($data);
        return $player;
    }

    public function delete($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();
        return response()->json(['message' => 'Jugador eliminado'], 204);
    }
}
