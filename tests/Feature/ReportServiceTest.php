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

class ReportServiceTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
      parent::setUp();

      $this->admin = User::factory()->create();
      $this->firstUser = User::factory()->create();  
      $this->month = Month::factory()->create(['user_id' => $this->firstUser->id]);
      $this->category = Category::factory()->create();
      $this->initialize_tests();  
  }

  public function test_admin_get_annual_comparison()
  {
      Passport::actingAs($this->admin);
      $response = $this->getJson('/api/reports/comparisons/annual/2025');
      $response->assertStatus(200);
      $response->assertJsonStructure([
          'records', 
          'total_ingresos', 
          'total_gastos', 
          'balance_neto'
      ]);
  }

  public function test_user_get_annual_comparison()
  {
      Passport::actingAs($this->firstUser);
      $response = $this->getJson('/api/reports/comparisons/annual/2025');
      $response->assertStatus(200);
      $response->assertJsonStructure([
          'records', 
          'total_ingresos', 
          'total_gastos', 
          'balance_neto'
      ]);
  }

  public function test_admin_get_Monthly_comparison()
  {
      Passport::actingAs($this->admin);
      $response = $this->getJson('/api/reports/comparisons/monthly/1980');
      $response->assertStatus(200);
      $response->assertJsonStructure([
          'records', 
          'total_ingresos', 
          'total_gastos', 
          'balance_neto'
      ]);
  }

  public function test_user_get_Monthly_comparison()
  {
      Passport::actingAs($this->firstUser);
      $response = $this->getJson('/api/reports/comparisons/monthly/1980');
      $response->assertStatus(200);
      $response->assertJsonStructure([
          'records', 
          'total_ingresos', 
          'total_gastos', 
          'balance_neto'
      ]);
  }

  public function test_get_annual_records_with_year_param()
  {
      Passport::actingAs($this->firstUser);

      $income1 = Income::factory()->create([
          'amount' => 1000,
          'description' => 'Salario',
          'category_id' => $this->category->id,
          'month_id' => $this->month->id,
      ]);

      $spent1 = Spent::factory()->create([
          'amount' => 200,
          'description' => 'Compra de alimentos',
          'category_id' => $this->category->id,
          'month_id' => $this->month->id,
      ]);

      $year = $this->month->year;

      $response = $this->getJson("/api/reports/comparisons/annual-report/{$year}");

      $response->assertStatus(200);
      $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
  }

  public function test_get_monthly_records_with_year_param()
  {
      Passport::actingAs($this->firstUser);

      $income1 = Income::factory()->create([
          'amount' => 1000,
          'description' => 'Salario',
          'category_id' => $this->category->id,
          'month_id' => $this->month->id,
      ]);

      $spent1 = Spent::factory()->create([
          'amount' => 200,
          'description' => 'Compra de alimentos',
          'category_id' => $this->category->id,
          'month_id' => $this->month->id,
      ]);

      $response = $this->getJson("/api/reports/comparisons/monthly-report/{$this->month->id}");

      $response->assertStatus(200);
      $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
  }
}