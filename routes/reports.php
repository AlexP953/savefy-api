<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::middleware('auth:api')->prefix('reports')->group(function () {
  
  // GET
  Route::get('/getExpensesMonth/{month}', [ReportController::class, 'getExpensesMonth']);

  Route::get('/getExpensesCategory/{category}', [ReportController::class, 'getExpensesCategory']);

  Route::get('/getIncomesMonth/{month}', [ReportController::class, 'getIncomesMonth']);

  Route::get('/getIncomesCategory/{category}', [ReportController::class, 'getIncomesCategory']);
  
  Route::get('/getIncomesYear/{year}', [ReportController::class, 'getIncomesYear']);

  Route::get('/getExpensesYear/{year}', [ReportController::class, 'getExpensesYear']);

  Route::get('/getAnnualComparison/{year}', [ReportController::class, 'getAnnualComparison']);
  Route::get('/getMonthlyComparison/{month}', [ReportController::class, 'getMonthlyComparison']);
  
  
  Route::get('/getAnnualComparisonReport/{year}', [ReportController::class, 'getAnnualComparisonReport']);
  Route::get('/getMonthlyComparisonReport/{month}', [ReportController::class, 'getMonthlyComparisonReport']);

});
