<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->get('/user', [UserController::class, 'show']);

Route::post('/users/create-user', [UserController::class, 'store']); // SIN auth:api

Route::middleware('auth:api')->prefix('users')->group(function () {

  Route::middleware('auth:api')->get('/', [UserController::class, 'index']);
  Route::middleware('auth:api')->get('/id/{id}', [UserController::class, 'getUserById']);
  Route::middleware('auth:api')->get('/email', [UserController::class, 'getUserByEmail']);

  Route::middleware('auth:api')->patch('/update', [UserController::class, 'update']);

  Route::middleware('auth:api')->delete('/delete-user/{id}', [UserController::class, 'destroy']);
});