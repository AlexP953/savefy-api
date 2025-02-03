<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpentController;

Route::middleware('auth:api')->prefix('spents')->group(function () {
  
  // GET
  Route::get('/', [SpentController::class, 'index']);
  Route::get('/my-spent', [SpentController::class, 'show']);
  Route::get('/id/{id}', [SpentController::class, 'getSpentById']);
  
  // POST
  Route::post('/create-spent', [SpentController::class, 'store']);

  // PATCH
  Route::patch('/update', [SpentController::class, 'update']);

  // DELETE
  Route::delete('/delete/{id}', [SpentController::class, 'destroy']);
});
