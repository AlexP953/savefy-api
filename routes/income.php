<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;

Route::middleware('auth:api')->prefix('incomes')->group(function () {
  
  Route::get('/', [IncomeController::class, 'index']);
  Route::get('/user', [IncomeController::class, 'show']);
  Route::get('/{id}', [IncomeController::class, 'getIncomeById']);
  
  Route::post('/', [IncomeController::class, 'store']);

  Route::patch('/{id}', [IncomeController::class, 'update']);

  Route::delete('/{id}', [IncomeController::class, 'destroy']);
});
