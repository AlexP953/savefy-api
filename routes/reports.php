<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::middleware('auth:api')->prefix('reports')->group(function () {
  
  Route::get('/expenses/by-month/{month_id}', [ReportController::class, 'getExpensesMonth']);
  Route::get('/expenses/by-category/{category_id}', [ReportController::class, 'getExpensesCategory']);
  Route::get('/expenses/by-year/{year}', [ReportController::class, 'getExpensesYear']);

  Route::get('/incomes/by-month/{month_id}', [ReportController::class, 'getIncomesMonth']);
  Route::get('/incomes/by-category/{category_id}', [ReportController::class, 'getIncomesCategory']);
  Route::get('/incomes/by-year/{year}', [ReportController::class, 'getIncomesYear']);

  Route::get('/comparisons/annual/{year}', [ReportController::class, 'getAnnualComparison']);
  Route::get('/comparisons/monthly/{month_id}', [ReportController::class, 'getMonthlyComparison']);
  
  
  Route::get('/comparisons/annual-report/{year}', [ReportController::class, 'getAnnualComparisonReport']);
  Route::get('/comparisons/monthly-report/{month_id}', [ReportController::class, 'getMonthlyComparisonReport']);

});
