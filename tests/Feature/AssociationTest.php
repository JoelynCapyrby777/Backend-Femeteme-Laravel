<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Association;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;
use PHPUnit\Framework\Attributes\Test;

class AssociationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    protected function autenticar(): string
    {
        $admin = User::factory()->create(['role_id' => 1]);
        return JWTAuth::fromUser($admin);
    }

    #[Test]
    public function puede_listar_todas_las_asociaciones()
    {
        $token = $this->autenticar();

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/asociaciones')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    #[Test]
    public function puede_mostrar_una_asociacion()
    {
        $token = $this->autenticar();
        $asociacion = Association::factory()->create();

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/asociaciones/{$asociacion->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $asociacion->id);
    }

    #[Test]
    public function retorna_404_si_no_encuentra_asociacion()
    {
        $token = $this->autenticar();

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/asociaciones/99999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Asociación no encontrada.');
    }

    #[Test]
    public function puede_crear_una_asociacion()
    {
        $token = $this->autenticar();
        $payload = [
            'name' => 'Test Association',
            'abbreviation' => 'TST',
        ];

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/asociaciones', $payload)
            ->assertCreated()
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.abbreviation', $payload['abbreviation']);

        $this->assertDatabaseHas('associations', $payload);
    }

    #[Test]
    public function valida_al_crear_una_asociacion()
    {
        $token = $this->autenticar();

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/asociaciones', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'abbreviation']);
    }

    #[Test]
    public function puede_actualizar_una_asociacion()
    {
        $token = $this->autenticar();
        $asociacion = Association::factory()->create();

        $payload = [
            'name' => 'Nombre Actualizado',
            'abbreviation' => $asociacion->abbreviation,
        ];

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/asociaciones/{$asociacion->id}", $payload)
            ->assertOk()
            ->assertJsonPath('message', 'La asociación se ha actualizado correctamente');

        $this->assertDatabaseHas('associations', [
            'id' => $asociacion->id,
            'name' => 'Nombre Actualizado',
        ]);
    }

    #[Test]
    public function valida_al_actualizar_una_asociacion()
    {
        $token = $this->autenticar();
        $asociacion = Association::factory()->create();

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/asociaciones/{$asociacion->id}", ['name' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function puede_eliminar_una_asociacion()
    {
        $token = $this->autenticar();
        $asociacion = Association::factory()->create();

        $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/asociaciones/{$asociacion->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('associations', ['id' => $asociacion->id]);
    }
}
