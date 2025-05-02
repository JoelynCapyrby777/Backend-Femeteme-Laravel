<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Association;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class AssociationAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Insertar roles en la base de datos
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Arbitro'],
            ['id' => 3, 'name' => 'Jugador'],
        ]);
    }

    private function getTokenForRole($roleId)
    {
        $user = User::factory()->create(['role_id' => $roleId]);
        return JWTAuth::fromUser($user);
    }

    private function authRequest($method, $uri, $token, $data = [])
    {
        return $this->withHeader('Authorization', "Bearer $token")->json($method, $uri, $data);
    }

    #[Test]
    public function jugador_puede_ver_solo_lista()
    {
        $token = $this->getTokenForRole(3);
        Association::factory()->create();

        $this->authRequest('GET', '/api/associations', $token)->assertOk();

        $this->authRequest('GET', '/api/associations/1', $token)->assertForbidden();
        $this->authRequest('POST', '/api/associations', $token, ['name' => 'X', 'abbreviation' => 'X'])->assertForbidden();
        $this->authRequest('PATCH', '/api/associations/1', $token, ['name' => 'Y'])->assertForbidden();
        $this->authRequest('DELETE', '/api/associations/1', $token)->assertForbidden();
    }

    #[Test]
    public function arbitro_puede_ver_y_crear()
    {
        $token = $this->getTokenForRole(2);
        $asoc = Association::factory()->create();

        $this->authRequest('GET', '/api/associations', $token)->assertOk();
        $this->authRequest("GET", "/api/associations/{$asoc->id}", $token)->assertOk();

        $this->authRequest('POST', '/api/associations', $token, [
            'name' => 'Nueva Asoc',
            'abbreviation' => 'NA',
        ])->assertCreated();

        $this->authRequest('PATCH', "/api/associations/{$asoc->id}", $token, ['name' => 'Editada'])->assertForbidden();
        $this->authRequest('DELETE', "/api/associations/{$asoc->id}", $token)->assertForbidden();
    }

    #[Test]
    public function admin_puede_todo()
    {
        $token = $this->getTokenForRole(1);
        $asoc = Association::factory()->create();

        $this->authRequest('GET', '/api/associations', $token)->assertOk();
        $this->authRequest("GET", "/api/associations/{$asoc->id}", $token)->assertOk();

        $this->authRequest('POST', '/api/associations', $token, [
            'name' => 'Admin Asoc',
            'abbreviation' => 'AA',
        ])->assertCreated();

        $this->authRequest('PATCH', "/api/associations/{$asoc->id}", $token, [
            'name' => 'Actualizada',
            'abbreviation' => $asoc->abbreviation,
        ])->assertOk();

        $this->authRequest('DELETE', "/api/associations/{$asoc->id}", $token)->assertStatus(204);
    }
}
