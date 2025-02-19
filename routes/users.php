<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->get('/user', [UserController::class, 'show']); // Current user

Route::post('/users', [UserController::class, 'store']); // Create user

Route::middleware('auth:api')->prefix('users')->group(function () {

  Route::get('/', [UserController::class, 'index']);
  Route::get('/{identifier}', [UserController::class, 'getUser']) 
    ->where('identifier', '.*');

  Route::patch('/{id}', [UserController::class, 'update']);

  Route::delete('/{id}', [UserController::class, 'destroy']);
});