<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        $pokemons = Pokemon::all()->map(function ($pokemon) {
            return [
                'id' => $pokemon->id,
                'nombre' => $pokemon->nombre,
                'imagen' => url(Storage::url($pokemon->imagen)),
            ];
        });

        return response()->json($pokemons);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->file('imagen')->store('pokemons', 'public');

        $pokemon = Pokemon::create([
            'nombre' => $request->input('nombre'),
            'imagen' => $imagePath,
        ]);

        return response()->json(['status' => 'success', 'pokemon' => $pokemon], 201);
    }

    public function update(Request $request, Pokemon $pokemon)
    {
        $request->validate([
            'nombre' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pokemon->nombre = $request->input('nombre');

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('pokemons', 'public');

            if ($pokemon->imagen) {
                Storage::disk('public')->delete($pokemon->imagen);
            }

            $pokemon->imagen = $imagePath;
        }

        $pokemon->save();

        return response()->json(['status' => 'success', 'pokemon' => $pokemon]);
    }

    public function destroy(Pokemon $pokemon)
    {
        if ($pokemon->imagen) {
            Storage::disk('public')->delete($pokemon->imagen);
        }

        $pokemon->delete();

        return response()->json(['message' => 'Pokémon deleted successfully']);
    }
}
