<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\User;
use App\Models\Association;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'curp' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9]{2}'),
            'age' => $this->faker->numberBetween(10, 50),
            'category' => $this->faker->randomElement(['femenil', 'varonil']),
            'ranking_position' => null,
            'association_id' => Association::factory(),
        ];
    }
}
