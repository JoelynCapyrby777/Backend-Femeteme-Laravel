<?php

namespace App\Http\Requests\TennisMatch;

use Illuminate\Foundation\Http\FormRequest;

class StoreTennisMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asegúrate de manejar la autorización según tus necesidades
    }

    public function rules(): array
    {
        return [
            'player_one_id' => 'required|exists:players,id|different:player_two_id',
            'player_two_id' => 'required|exists:players,id',
            'winner_id' => 'required|in:' . $this->player_one_id . ',' . $this->player_two_id,
            'event_type' => 'required|in:femeteme,no_oficial',
        ];
    }
}
