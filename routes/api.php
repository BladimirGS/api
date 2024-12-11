<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\UserController;

Route::get('/pokemon', [PokemonController::class, 'index']);

Route::post('pokemon/create', [PokemonController::class, 'store']);

Route::put('/pokemon/update/{id}', [PokemonController::class, 'update']);

Route::delete('pokemon/delete/{id}', [PokemonController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/users', [UserController::class, 'index']);

Route::post('/users/create', [UserController::class, 'store']);

Route::put('/users/update/{id}', [UserController::class, 'update']);

Route::delete('users/delete/{id}', [UserController::class, 'destroy']);
