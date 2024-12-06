<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::apiResource('pokemon', PokemonController::class);

Route::get('/pokemon', [PokemonController::class, 'index']);

Route::put('/pokemon/{id}', [PokemonController::class, 'update']);

Route::delete('pokemon/{id}/delete', [PokemonController::class, 'destroy']);

