<?php

namespace App\Services;

use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PlayerService
{
    public function obtenerTodos()
    {
        $jugadores = Player::with(['user:id,name,email', 'association:id,name'])->get();

        if ($jugadores->isEmpty()) {
            return ['error' => 'No hay jugadores registrados', 'status' => 404];
        }

        $jugadoresModificados = $jugadores->map(function ($jugador) {
            $data = $jugador->toArray();
            $data['ranking_position'] = $jugador->ranking_position ?? 'No asignado';
            return $data;
        });

        return ['data' => $jugadoresModificados, 'status' => 200];
    }

    public function crear(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'curp' => 'required|string|max:18|unique:players',
            'age' => 'required|integer|min:5|max:100',
            'category' => 'required|in:femenil,varonil',
            'association_id' => 'required|exists:associations,id',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors(), 'status' => 422];
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 3, // Jugador
        ]);

        $player = Player::create([
            'user_id' => $user->id,
            'curp' => $data['curp'],
            'age' => $data['age'],
            'category' => $data['category'],
            'ranking_position' => null, // No se asigna al crear
            'association_id' => $data['association_id'],
        ]);

        $formateado = $player->load('user', 'association')->toArray();
        $formateado['ranking_position'] = 'No asignado';

        return ['data' => $formateado, 'status' => 201];
    }

    public function obtenerPorId($id)
    {
        $jugador = Player::with(['user:id,name,email', 'association:id,name'])->find($id);

        if (!$jugador) {
            return ['error' => 'Jugador no encontrado', 'status' => 404];
        }

        $data = $jugador->toArray();
        $data['ranking_position'] = $jugador->ranking_position ?? 'No asignado';

        return ['data' => $data, 'status' => 200];
    }

    public function modificar(array $data, $id)
    {
        $jugador = Player::find($id);

        if (!$jugador) {
            return ['error' => 'Jugador no encontrado', 'status' => 404];
        }

        $validator = Validator::make($data, [
            'curp' => "sometimes|string|max:18|unique:players,curp,{$id}",
            'age' => 'sometimes|integer|min:5|max:100',
            'category' => 'sometimes|in:femenil,varonil',
            'association_id' => 'sometimes|exists:associations,id',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors(), 'status' => 422];
        }

        $jugador->update($data);

        return ['message' => 'Jugador actualizado correctamente', 'status' => 200];
    }

    public function eliminar($id)
    {
        $jugador = Player::find($id);

        if (!$jugador) {
            return ['error' => 'Jugador no encontrado', 'status' => 404];
        }

        $jugador->delete();

        return ['message' => 'Jugador eliminado correctamente', 'status' => 204];
    }
}
