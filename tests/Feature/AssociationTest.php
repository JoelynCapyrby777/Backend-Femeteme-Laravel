<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Association;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssociationTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
{
    $admin = User::factory()->create(['role_id' => 1]);
    return JWTAuth::fromUser($admin);
}

    /** @test */
    public function it_can_list_all_asociaciones()
    {
        Association::factory()->count(3)->create();
        $token = $this->authenticate();
$this->withHeader('Authorization', "Bearer $token")
     ->getJson('/api/associations')
     ->assertOk();


        $this->getJson('/api/asociaciones')
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_single_association()
    {
        $assoc = Association::factory()->create();
        $token = $this->authenticate();
$this->withHeader('Authorization', "Bearer $token")
     ->getJson('/api/associations')
     ->assertOk();


        $this->getJson("/api/asociaciones/{$assoc->id}")
             ->assertStatus(200)
             ->assertJsonPath('data.id', $assoc->id);
    }

    /** @test */
    public function it_returns_404_if_association_not_found()
    {
        $token = $this->authenticate();
$this->withHeader('Authorization', "Bearer $token")
     ->getJson('/api/associations')
     ->assertOk();

        $this->getJson('/api/asociaciones/999')
             ->assertStatus(404)
             ->assertJson(['message' => 'Asociación no encontrada.']);
    }

    /** @test */
    public function it_can_create_an_association()
    {
        $payload = ['name' => 'Test', 'abbreviation' => 'TST'];
        $token = $this->authenticate();
$this->withHeader('Authorization', "Bearer $token")
     ->getJson('/api/associations')
     ->assertOk();


        $this->postJson('/api/asociaciones', $payload)
             ->assertStatus(201)
             ->assertJsonPath('data.name', 'Test')
             ->assertJsonPath('data.abbreviation', 'TST');

        $this->assertDatabaseHas('asociaciones', $payload);
    }

    /** @test */
    public function it_validates_when_creating_an_association()
    {
        $this->postJson('/api/asociaciones', [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'abbreviation']);
    }

    /** @test */
    public function it_can_update_an_association()
    {
        $assoc = Association::factory()->create();
        $payload = ['name' => 'Updated'];

        $this->putJson("/api/asociaciones/{$assoc->id}", $payload)
             ->assertStatus(200)
             ->assertJsonPath('data.name', 'Updated');

        $this->assertDatabaseHas('asociaciones', ['id' => $assoc->id, 'name' => 'Updated']);
    }

    /** @test */
    public function it_can_delete_an_association()
    {
        $assoc = Association::factory()->create();

        $this->deleteJson("/api/asociaciones/{$assoc->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('asociaciones', ['id' => $assoc->id]);
    }
}
