<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Player;
use App\Models\Association;
use Illuminate\Support\Facades\Hash;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $association = Association::factory()->create();

        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Jugador $i",
                'email' => "jugador{$i}@example.com",
                'password' => Hash::make('password'),
                'role_id' => 3, 
            ]);

            Player::create([
                'user_id' => $user->id,
                'curp' => 'CURPTEST' . str_pad($i, 10, '0', STR_PAD_LEFT),
                'age' => rand(12, 40),
                'category' => $i % 2 === 0 ? 'femenil' : 'varonil',
                'ranking_position' => null,
                'association_id' => $association->id,
            ]);
        }
    }
}
