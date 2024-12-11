<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'nombre' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = $request->file('imagen');
        $imagePath = $image->store('pokemons', 'public');

        $pokemon = Pokemon::create([
            'nombre' => $request->input('nombre'),
            'imagen' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'pokemon' => $pokemon,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pokemon = Pokemon::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'nullable',
        ]);

        $pokemon->nombre = $request->input('nombre');

        if ($request->hasFile('imagen')) {
            $image = $request->file('imagen');
            $imagePath = $image->store('pokemons', 'public');

            if ($pokemon->imagen) {
                Storage::disk('public')->delete($pokemon->imagen);
            }

            $pokemon->imagen = $imagePath;
        }
        if ($request->filled('imagen')) {
            $pokemon->imagen = "pokemon/" . $request->input('imagen');
        }

        $pokemon->save();

        return response()->json([
            'status' => 'success',
            'pokemon' => $pokemon,
        ], 200);
    }

    public function destroy($id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['message' => 'Pokémon not found'], 404);
        }

        if (Storage::exists($pokemon->imagen)) {
            Storage::delete($pokemon->imagen);
        }

        $pokemon->delete();

        return response()->json(['message' => 'Pokémon deleted successfully']);
    }
}
