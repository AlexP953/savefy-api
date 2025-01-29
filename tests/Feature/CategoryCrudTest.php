<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role; 
use App\Models\Category;
use Laravel\Passport\Passport;

class CategoryCrudTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->initialize_tests();
    }

    public function createCategoryForUser($user)
    {
        return Category::factory()->create(
            [
                'name' => 'Categoria de prueba',
                'user_id' => $user->id,
            ]
        );
    }

// Create


    public function test_user_create_category(){
        Passport::actingAs($this->firstUser);
        $response = $this->createCategoryForUser($this->firstUser);

        $this->assertDatabaseHas('categories', [
            'name' => 'Categoria de prueba',
            'user_id' => $this->firstUser->id,
        ]);
    }

    public function test_user_dont_create_repeated_category(){
        Passport::actingAs($this->firstUser);
        $response = $this->createCategoryForUser($this->firstUser);

        $response = $this->postJson('/api/categories/create-category', [
            'name' => 'Categoria de prueba',  
            'user_id' => $this->firstUser->id
        ]);
        $response->assertStatus(500);
    }

    public function test_user_dont_create_category_to_other_user(){
        Passport::actingAs($this->firstUser);
        $response = $this->postJson('/api/categories/create-category', [
            'name' => 'Categoria de prueba',
            'user_id' => 111,
        ]);
        
        $response->assertStatus(500);
    }

    // READ

    public function test_categories_exists()
    {
        Passport::actingAs($this->firstUser);
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Nómina',
        ]);
    }

    public function test_user_dont_read_all_categories(){
        Passport::actingAs($this->firstUser);
        $response = $this->getJson("/api/categories/my_categories");
        $response->assertStatus(200);
    
        $userIds = collect($response->json())->pluck('user_id');
        $userIds->each(function ($id) {
            $this->assertTrue(
                $id === null || $id === $this->firstUser->id
            );
        });
    }

    public function test_get_one_category_info($category = 'Ocio'){
        Passport::actingAs($this->firstUser);
        $response = $this->getJson("/api/categories/my_categories/filter?category=$category");
        $response->assertStatus(200);
        $categories = collect($response->json());

        $categories->each(function ($categoryData) use ($category) {
            $this->assertTrue(
                $categoryData['category_name'] === $category
            );
        });
    }

    public function test_get_category_by_id(){
        Passport::actingAs($this->firstUser);
        $response = $this->getJson("/api/categories/id/11");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Deporte',
        ]);
    }

    // UPDATE
    public function test_update_category(){
        Passport::actingAs($this->firstUser);
        $testCategory = $this->createCategoryForUser($this->firstUser);
        
        $updatedData = [
            "name" => $testCategory->name . 'test',
            "user_id" => $this->firstUser->id,
        ];
            $response = $this->patchJson("/api/categories/{$testCategory->id}", $updatedData);
    
        $response->assertStatus(200);
    
        $response->assertJsonFragment([
            "name" => $updatedData['name'],
        ]);
    }

    public function test_update_category_from_other_user(){
        Passport::actingAs($this->firstUser);
        $testCategory = $this->createCategoryForUser($this->firstUser);
        
        $updatedData = [
            "name" => $testCategory->name . 'test',
            "user_id" => $this->firstUser->id+5,
        ];

        $response = $this->patchJson("/api/categories/{$testCategory->id}", $updatedData);
    
        $response->assertStatus(500);
    }

    // DELETE
    public function test_delete_category(){
        $testCategory = $this->createCategoryForUser($this->firstUser);
        Passport::actingAs($this->admin);
        $this->deleteJson("/api/categories/delete/{$testCategory->id}");
        $this->assertDatabaseMissing('categories', [
            'id' => $testCategory->id,
        ]);
    }

    public function test_delete_category_from_other_user(){
        $testCategory = $this->createCategoryForUser($this->firstUser);
        Passport::actingAs($this->secondUser);
        $response = $this->deleteJson("/api/categories/delete/{$testCategory->id}");
        $response->assertStatus(500);
        $this->assertDatabaseHas('categories', [
            'name' => 'Categoria de prueba',
            'user_id' => $this->firstUser->id,
        ]);
    }


}
