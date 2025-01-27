<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Laravel\Passport\Passport;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->initialize_tests();
    }

    // READ

    public function test_get_all_users()
    {
        Passport::actingAs($this->admin);
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $this->admin->email,
        ]);
    }

    public function test_user_dont_read_all_users()
    {
        Passport::actingAs($this->user);
        $response = $this->getJson('/api/users');
        $response->assertStatus(403);
    }

    public function test_get_one_user()
    {
        Passport::actingAs($this->user);
        $response = $this->getJson('/api/user');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $this->user->email,
        ]);
    }

    public function test_get_user_by_id()
    {
        Passport::actingAs($this->admin);
        $response = $this->getJson("/api/users/id/{$this->user->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $this->user->email,
        ]);
    }

    public function test_get_user_by_wrong_id()
    {
        Passport::actingAs($this->admin);
        $response = $this->getJson("/api/users/{9999999999999}");
        $response->assertStatus(404);
    }

    // CREATE

    public function test_create_user()
    {
        Passport::actingAs($this->user);
        $newUserData = [
            'name' => 'Nuevo Usuario',
            'surname' => 'Apellido',
            'email' => 'nuevos12@usuario.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        $response = $this->postJson('/api/create-user/', $newUserData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Usuario creado con éxito',
        ]);
    }

    public function test_create_user_with_repeated_email()
    {
        Passport::actingAs($this->user);
        $newUserData = [
            'name' => 'Nuevo Usuario',
            'surname' => 'Apellido',
            'email' => 'alexperis95@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        // Create first user
        $this->postJson('/api/create-user/', $newUserData);
        // Second user
        $response = $this->postJson('/api/create-user/', $newUserData);

        $response->assertStatus(500);
    }

    // UPDATE

    public function test_update_user()
    {
        Passport::actingAs($this->admin);
        $updatedData = [
            'id' => $this->user->id,
            'name' => 'Nuevo Nombre',
            'surname' => 'Nuevo Apellido',
            'email' => 'nuevo@usuario.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        $response = $this->patchJson("/api/update", $updatedData);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Usuario actualizado correctamente.',
            'user' => [
                'id' => $updatedData['id'],
                'name' => $updatedData['name'],
                'surname' => $updatedData['surname'],
                'email' => $updatedData['email'],
            ],
        ]);
    }

    // DELETE

    public function test_delete_user()
    {
        Passport::actingAs($this->admin);

        $newUserData = [
            'name' => 'Nuevo Usuario',
            'surname' => 'Apellido',
            'email' => 'nuevos12@usuario.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->postJson('/api/create-user/', $newUserData);
        $newUser = $this->getJson("/api/users/email?email=".$newUserData['email']);
        $response = $this->deleteJson("/api/delete-user/{$newUser->json('id')}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }

    public function test_delete_user_without_admin()
    {
        Passport::actingAs($this->user);

        $newUserData = [
            'name' => 'Nuevo Usuario',
            'surname' => 'Apellido',
            'email' => 'nuevos12@usuario.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        $response = $this->postJson('/api/create-user/', $newUserData);
        $newUser = $this->getJson("/api/users/email?email=".$newUserData['email']);
        $response = $this->deleteJson("/api/delete-user/{$newUser->json('id')}");

        $response->assertStatus(403);
    }
}
