<?php

namespace App\Services;

use App\Models\Player;
use App\Models\TennisMatch;
use App\Models\RankingPointRule;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Player\PlayerNotFoundException;

class TennisMatchService
{
    public function crear(array $data): TennisMatch
    {
        return DB::transaction(function () use ($data) {
            $playerOne = Player::find($data['player_one_id']);
            $playerTwo = Player::find($data['player_two_id']);
            $winner = Player::find($data['winner_id']);

            if (! $playerOne || ! $playerTwo || ! $winner) {
                throw new PlayerNotFoundException;
            }

            $loser = $winner->id === $playerOne->id ? $playerTwo : $playerOne;
            $diff = abs(($winner->ranking_position ?? 0) - ($loser->ranking_position ?? 0));

            $rule = RankingPointRule::where('type', $data['event_type'])
                ->where('min_difference', '<=', $diff)
                ->where(function ($query) use ($diff) {
                    $query->where('max_difference', '>=', $diff)
                          ->orWhereNull('max_difference');
                })
                ->first();

            $positivePoints = $rule?->positive_points ?? 0;
            $negativePoints = $rule?->negative_points ?? 0;

            $winner->ranking_position = max(0, ($winner->ranking_position ?? 0) - $positivePoints);
            $loser->ranking_position = ($loser->ranking_position ?? 0) + $negativePoints;

            $winner->save();
            $loser->save();

            return TennisMatch::create([
                ...$data,
                'point_difference' => $diff,
            ]);
        });
    }

    public function actualizar(array $data, $id): TennisMatch
{
    $match = TennisMatch::findOrFail($id);

    $match->update($data);

    return $match;
}

}
