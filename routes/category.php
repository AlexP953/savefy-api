<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::middleware('auth:api')->prefix('categories')->group(function () {
  
  // GET
  Route::get('/', [CategoryController::class, 'index']);
  Route::get('/my_categories', [CategoryController::class, 'show']);
  Route::get('/my_categories/filter', [CategoryController::class, 'getOneCategorySpents']);
  Route::get('/id/{id}', [CategoryController::class, 'getCategoryById']);

  // POST
  Route::post('/create-category', [CategoryController::class, 'store']);

  // PATCH
  Route::patch('/{id}', [CategoryController::class, 'update']);

  // DELETE
  Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
});