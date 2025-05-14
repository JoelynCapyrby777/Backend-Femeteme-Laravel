<?php

namespace App\Services;

use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Player\PlayerNotFoundException;

class PlayerService
{
    public function obtenerTodos()
    {
        return Player::with(['user:id,name,email', 'association:id,name'])->get();
    }

    public function obtenerPorId($id)
    {
        $player = Player::with(['user:id,name,email', 'association:id,name'])->find($id);

        if (! $player) {
            throw new PlayerNotFoundException();
        }

        return $player;
    }

    public function crear(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => 3, // Rol Jugador
        ]);

        $player = Player::create([
            'user_id'       => $user->id,
            'curp'          => $data['curp'],
            'age'           => $data['age'],
            'category'      => $data['category'],
            'association_id'=> $data['association_id'],
            'ranking_position' => null,
        ]);

        return $player->load('user', 'association');
    }

    public function modificar(array $data, $id)
    {
        $player = Player::find($id);

        if (! $player) {
            throw new PlayerNotFoundException();
        }

        $player->update($data);

        return $player->load('user', 'association');
    }

    public function eliminar($id)
    {
        $player = Player::find($id);

        if (! $player) {
            throw new PlayerNotFoundException();
        }

        $player->delete();
    }
}
