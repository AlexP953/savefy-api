<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->get('/user', [UserController::class, 'show']);
Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);
