<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Month;
use App\Models\Category;
use App\Models\Income;
use App\Models\Spent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class FinancialServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->initialize_tests();

        $this->month = Month::factory()->create(['user_id' => $this->firstUser->id]);
        $this->category = Category::factory()->create();
    }

    // GET ALL RECORDS
    public function test_admin_get_all_incomes()
    {
        Passport::actingAs($this->admin);
        $response = $this->getJson('/api/incomes');
        $response->assertStatus(200);
    }

    public function test_user_get_all_incomes()
    {
        Passport::actingAs($this->firstUser);
        $response = $this->getJson('/api/incomes');
        $response->assertStatus(403);
    }

    public function test_admin_get_all_spents()
    {
        Passport::actingAs($this->admin);
        $response = $this->getJson('/api/spents');
        $response->assertStatus(200);
    }

    public function test_user_get_all_spents()
    {
        Passport::actingAs($this->firstUser);
        $response = $this->getJson('/api/spents');
        $response->assertStatus(403);
    }

    // // GET ONE RECORD
    public function test_get_one_income()
    {
        Passport::actingAs($this->firstUser);
        $income = Income::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        $response = $this->getJson("/api/incomes/id/{$income->id}");
        $response->assertStatus(200);
    }

    public function test_get_one_spent()
    {
        Passport::actingAs($this->firstUser);
        $spent = Spent::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        $response = $this->getJson("/api/spents/id/{$spent->id}");
        $response->assertStatus(200);
    }

    // // CREATE RECORD
    public function test_create_income()
    {
        Passport::actingAs($this->firstUser);

        $data = [
            'amount' => 1000,
            'description' => 'Salario',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->postJson('/api/incomes/create-income', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment(['description' => 'Salario']);
    }

    public function test_create_spent()
    {
        Passport::actingAs($this->firstUser);

        $data = [
            'amount' => 200,
            'description' => 'Compra de alimentos',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->postJson('/api/spents/create-spent', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment(['description' => 'Compra de alimentos']);
    }

    public function test_create_income_with_string()
    {
        Passport::actingAs($this->firstUser);

        $data = [
            'amount' => 'X',
            'description' => 'Salario',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->postJson('/api/incomes/create-income', $data);
        $response->assertStatus(500);
    }

    public function test_create_spent_with_string()
    {
        Passport::actingAs($this->firstUser);

        $data = [
            'amount' => 'NONE',
            'description' => 'Compra de alimentos',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->postJson('/api/spents/create-spent', $data);
        $response->assertStatus(500);
    }

    // UPDATE RECORD
    public function test_update_income()
    {
        $income = Income::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        Passport::actingAs($this->firstUser);

        $data = [
            'id' => $income->id,
            'amount' => 1200,
            'description' => 'Salario actualizado',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->patchJson('/api/incomes/update', $data);
        $response->assertStatus(200);
    }

    public function test_update_spent()
    {
        $spent = Spent::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        Passport::actingAs($this->firstUser);

        $data = [
            'id' => $spent->id,
            'amount' => 250,
            'description' => 'Compra modificada',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->patchJson('/api/spents/update', $data);
        $response->assertStatus(200);
    }

    public function test_update_income_with_string()
    {
        $income = Income::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        Passport::actingAs($this->firstUser);

        $data = [
            'id' => $income->id,
            'amount' => 'None',
            'description' => 'Salario actualizado',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->patchJson('/api/incomes/update', $data);
        $response->assertStatus(500);
    }

    public function test_update_spent_with_string()
    {
        $spent = Spent::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        Passport::actingAs($this->firstUser);

        $data = [
            'id' => $spent->id,
            'amount' => 'None',
            'description' => 'Compra modificada',
            'category_id' => $this->category->id,
            'month_id' => $this->month->id,
        ];

        $response = $this->patchJson('/api/spents/update', $data);
        $response->assertStatus(500);
    }

    // DELETE RECORD
    public function test_delete_income()
    {
        $income = Income::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        Passport::actingAs($this->firstUser);

        $response = $this->deleteJson("/api/incomes/delete/{$income->id}");
        $response->assertStatus(200);
    }

    public function test_delete_spent()
    {
        $spent = Spent::factory()->create(['month_id' => $this->month->id, 'category_id' => $this->category->id]);
        Passport::actingAs($this->firstUser);

        $response = $this->deleteJson("/api/spents/delete/{$spent->id}");
        $response->assertStatus(200);
    }
}
