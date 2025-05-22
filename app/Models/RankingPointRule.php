<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingPointRule extends Model
{
     use HasFactory;

    protected $fillable = [
        'match_type',
        'min_difference',
        'max_difference',
        'points_for_winner',
        'points_for_loser',
    ];
}
