<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpentController;

Route::middleware('auth:api')->prefix('spents')->group(function () {
  
  Route::get('/', [SpentController::class, 'index']); 
  Route::get('/user', [SpentController::class, 'show']);
  Route::get('/{id}', [SpentController::class, 'getSpentById']);
  
  Route::post('/', [SpentController::class, 'store']);

  Route::patch('/{id}', [SpentController::class, 'update']);

  Route::delete('/{id}', [SpentController::class, 'destroy']);
});