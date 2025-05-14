<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $playerId = $this->route('player') ?? $this->route('id');
        return [
            'name'           => 'sometimes|required|string|min:3|max:100',
            'email'          => 'sometimes|required|email|max:100|unique:users,email,' . $playerId,
            'curp'           => 'sometimes|required|string|max:18|unique:players,curp,' . $playerId,
            'age'            => 'sometimes|required|integer|min:5|max:100',
            'category'       => 'sometimes|required|in:femenil,varonil',
            'association_id' => 'sometimes|required|exists:associations,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'El nombre del jugador es obligatorio.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.unique'            => 'Ya existe un jugador con este correo electrónico.',
            'curp.required'           => 'La CURP es obligatoria.',
            'curp.unique'             => 'Ya existe un jugador con esta CURP.',
            'age.required'            => 'La edad es obligatoria.',
            'category.required'       => 'La categoría es obligatoria.',
            'association_id.required' => 'La asociación es obligatoria.',
        ];
    }
}
