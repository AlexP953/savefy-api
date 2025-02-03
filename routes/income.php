<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;

Route::middleware('auth:api')->prefix('incomes')->group(function () {
  
  // GET
  Route::get('/', [IncomeController::class, 'index']);
  Route::get('/my-income', [IncomeController::class, 'show']);
  Route::get('/id/{id}', [IncomeController::class, 'getIncomeById']);
  
  // POST
  Route::post('/create-income', [IncomeController::class, 'store']);

  // PATCH
  Route::patch('/update', [IncomeController::class, 'update']);

  // DELETE
  Route::delete('/delete/{id}', [IncomeController::class, 'destroy']);
});
