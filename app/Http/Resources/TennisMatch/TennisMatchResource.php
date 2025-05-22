<?php

namespace App\Http\Resources\TennisMatch;

use Illuminate\Http\Resources\Json\JsonResource;

class TennisMatchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'player_one_id' => $this->player_one_id,
            'player_two_id' => $this->player_two_id,
            'winner_id' => $this->winner_id,
            'event_type' => $this->event_type,
            'point_difference' => $this->point_difference,
            'created_at' => $this->created_at,
        ];
    }
}
