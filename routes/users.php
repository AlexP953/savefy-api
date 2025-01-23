<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->get('/user', [UserController::class, 'show']);
Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);
Route::middleware('auth:api')->get('/users/id/{id}', [UserController::class, 'getUserById']);
Route::middleware('auth:api')->get('/users/email', [UserController::class, 'getUserByEmail']);

Route::middleware('auth:api')->post('/create-user', [UserController::class, 'store']);

Route::middleware('auth:api')->patch('/update', [UserController::class, 'update']);

Route::middleware('auth:api')->delete('/delete-user/{id}', [UserController::class, 'destroy']);