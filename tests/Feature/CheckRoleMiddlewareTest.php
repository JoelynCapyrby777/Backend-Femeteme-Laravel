<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Insertar roles si no existen
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Arbitro'],
            ['id' => 3, 'name' => 'Jugador'],
        ]);

        // Registrar una ruta temporal para pruebas
        Route::middleware(['auth:api', 'check.role:1,2'])->get('/api/prueba-rol', function () {
            return response()->json(['message' => 'Acceso permitido'], 200);
        });
    }

    public function test_admin_puede_acceder()
    {
        $admin = User::factory()->create(['role_id' => 1]);
        $token = JWTAuth::fromUser($admin);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->get('/api/prueba-rol');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Acceso permitido']);
    }

    public function test_arbitro_puede_acceder()
    {
        $arbitro = User::factory()->create(['role_id' => 2]);
        $token = JWTAuth::fromUser($arbitro);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->get('/api/prueba-rol');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Acceso permitido']);
    }

    public function test_jugador_no_puede_acceder()
    {
        $jugador = User::factory()->create(['role_id' => 3]);
        $token = JWTAuth::fromUser($jugador);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->get('/api/prueba-rol');

        $response->assertStatus(403)
                 ->assertJsonFragment(['message' => 'Acceso denegado']);
    }
}
