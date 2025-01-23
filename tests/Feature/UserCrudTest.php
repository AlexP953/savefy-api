<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Spatie\Permission\Models\Role; 
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

class UserCrudTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // Create an admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Create an user
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
        // 
    }

    // READ

    public function testGetAllUsers()
    {
        Passport::actingAs($this->admin);
        $response = $this->getJson('/api/users');
        $response->assertStatus(200); 
        $response->assertJsonFragment([
            'email' => $this->admin->email,
        ]);
    }
    

    public function testUserDontReadAllUsers()
    {
        Passport::actingAs($this->user);
        $response = $this->getJson('/api/users');
        $response->assertStatus(403); 

    }

    public function testGetOneUser()
    {
        Passport::actingAs($this->user);
        $response = $this->getJson('/api/user');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $this->user->email,
        ]); 
    }

    public function testGetUserById()
{
    Passport::actingAs($this->admin); 
    $response = $this->getJson("/api/users/id/{$this->user->id}");
    $response->assertStatus(200); 
    echo $this->user->email;
    $response->assertJsonFragment([
        'email' => $this->user->email,
    ]);
}

    public function testGetUserByWrongId()
{
    Passport::actingAs($this->admin); 
    $response = $this->getJson("/api/users/{9999999999999}");
    $response->assertStatus(404); 
}


    // CREATE

    public function testCreateUser()
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

    public function testCreateUserWithRepeatedEmail()
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

    public function testUpdateUser()
    {
        Passport::actingAs($this->admin);
        $updatedData = [
            'id' => 1,
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

    public function testDeleteUser()
    {
        Passport::actingAs($this->admin);

        $newUserData = [
            'name' => 'Nuevo Usuario',
            'surname' => 'Apellido',
            'email' => 'nuevos12@usuario.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        $response = $this->postJson('/api/create-user/', $newUserData);
        $newUser = $this->getJson("/api/users/email?email=".$newUserData['email']);
        $response = $this->deleteJson("/api/delete-user/{$this->user->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }

    public function testDeleteUserWithoutAdmin()
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
        $response = $this->deleteJson("/api/delete-user/{$this->user->id}");

        $response->assertStatus(403);
    }
}
