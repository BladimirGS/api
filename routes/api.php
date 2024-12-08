<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/pokemon', [PokemonController::class, 'index']);

Route::post('/pokemon/update/{id}', [PokemonController::class, 'update']);

Route::post('pokemon/create', [PokemonController::class, 'store']);

Route::delete('pokemon/delete/{id}', [PokemonController::class, 'destroy']);
