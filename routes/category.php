<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::middleware('auth:api')->prefix('categories')->group(function () {
  
  Route::get('/', [CategoryController::class, 'index']);
  Route::get('/user', [CategoryController::class, 'show']);
  Route::get('/user/filter', [CategoryController::class, 'getOneCategorySpents']);    
  Route::get('/{id}', [CategoryController::class, 'getCategoryById']);

  Route::post('/', [CategoryController::class, 'store']);

  Route::patch('/{id}', [CategoryController::class, 'update']);

  Route::delete('/{id}', [CategoryController::class, 'destroy']);
});