<?php

namespace App\Services;

use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Player\PlayerNotFoundException;
use App\Exceptions\Player\PlayerValidationException;
use App\Exceptions\Player\PlayerConflictException;

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
        $validator = Validator::make($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'curp'     => 'required|string|max:18|unique:players,curp',
            'age'      => 'required|integer|min:5|max:100',
            'category' => 'required|in:femenil,varonil',
            'association_id' => 'required|exists:associations,id',
        ]);

        if ($validator->fails()) {
            throw new PlayerValidationException($validator->errors()->toJson());
        }

        // Validación adicional (por si cambia lógica en el futuro)
        if (User::where('email', $data['email'])->exists()) {
            throw new PlayerConflictException('El correo ya está registrado.');
        }

        if (Player::where('curp', $data['curp'])->exists()) {
            throw new PlayerConflictException('La CURP ya está registrada.');
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => 3,
        ]);

        $player = Player::create([
            'user_id'          => $user->id,
            'curp'             => $data['curp'],
            'age'              => $data['age'],
            'category'         => $data['category'],
            'association_id'   => $data['association_id'],
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

        $validator = Validator::make($data, [
            'curp'     => 'required|string|max:18|unique:players,curp,' . $id,
            'age'      => 'required|integer|min:5|max:100',
            'category' => 'required|in:femenil,varonil',
            'association_id' => 'required|exists:associations,id',
        ]);

        if ($validator->fails()) {
            throw new PlayerValidationException($validator->errors()->toJson());
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
