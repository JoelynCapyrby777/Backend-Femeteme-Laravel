<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Player;
use App\Models\User;
use App\Models\Association;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;
use PHPUnit\Framework\Attributes\Test;

class PlayerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    protected function authenticate(): string
    {
        $admin = User::factory()->create(['role_id' => 1]);
        return JWTAuth::fromUser($admin);
    }

    #[Test]
public function puede_listar_tres_jugadores_existentes()
{
    $token = $this->authenticate();

    $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/jugadores')
        ->assertOk()
        ->assertJson(fn ($json) =>
            $json->has('data', 3) // Espera exactamente 3 registros
        );
}




    #[Test]
    public function puede_mostrar_un_jugador()
    {
        $token = $this->authenticate();
        $association = Association::factory()->create();
        $player = Player::factory()->create(['association_id' => $association->id]);

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/jugadores/{$player->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $player->id);
    }

    #[Test]
    public function devuelve_404_si_el_jugador_no_existe()
    {
        $token = $this->authenticate();

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/jugadores/999')
            ->assertStatus(404)
            ->assertJsonPath('message', 'Jugador no encontrado.');
    }

    #[Test]
    public function puede_crear_un_jugador()
    {
        $token = $this->authenticate();
        $association = Association::factory()->create();

        $payload = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'curp' => 'CURP1234567890123',
            'age' => 25,
            'category' => 'varonil',
            'association_id' => $association->id,
        ];

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/jugadores', $payload)
            ->assertStatus(201)
            ->assertJsonPath('data.curp', 'CURP1234567890123');
    }

    #[Test]
    public function valida_datos_al_crear_un_jugador()
    {
        $token = $this->authenticate();

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/jugadores', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'curp', 'age', 'category', 'association_id']);
    }

    #[Test]
    public function puede_actualizar_un_jugador()
    {
        $token = $this->authenticate();
        $association = Association::factory()->create();
        $player = Player::factory()->create(['association_id' => $association->id]);

        $payload = ['age' => 30];

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/jugadores/{$player->id}", $payload)
            ->assertStatus(200)
            ->assertJsonPath('message', 'Jugador actualizado correctamente');

        $this->assertDatabaseHas('players', ['id' => $player->id, 'age' => 30]);
    }

    #[Test]
    public function puede_eliminar_un_jugador()
    {
        $token = $this->authenticate();
        $association = Association::factory()->create();
        $player = Player::factory()->create(['association_id' => $association->id]);

        $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/jugadores/{$player->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }
}
