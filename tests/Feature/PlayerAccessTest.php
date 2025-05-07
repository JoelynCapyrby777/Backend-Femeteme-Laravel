<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Association;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class PlayerAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Arbitro'],
            ['id' => 3, 'name' => 'Jugador'],
        ]);
    }

    private function getTokenForAdmin()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'password' => Hash::make('secret'),
        ]);

        return JWTAuth::fromUser($admin);
    }

    #[Test]
    public function admin_can_crud_players()
    {
        $token = $this->getTokenForAdmin();

        // Crear asociación
        $association = Association::factory()->create();

        // Crear jugador
        $data = [
            'name' => 'Juan Tenista',
            'email' => 'juan@example.com',
            'password' => 'secret123',
            'curp' => 'CURPJUAN123456789',
            'age' => 23,
            'category' => 'varonil',
            'ranking_position' => 4,
            'association_id' => $association->id,
        ];

        $create = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/jugadores', $data)
            ->assertCreated();

        $playerId = $create->json('data.id');

        // Ver jugador
        $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/jugadores/{$playerId}")
            ->assertOk();

        // Modificar jugador
        $this->withHeader('Authorization', "Bearer $token")
        ->patchJson("/api/jugadores/{$playerId}", ['age' => 25])
        ->assertOk();


        // Eliminar jugador
        $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/jugadores/{$playerId}")
            ->assertNoContent();
    }
}
