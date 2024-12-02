<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PokemonController extends Controller
{
    // Obtener todos los Pokémon
    public function index()
    {
        $pokemons = Pokemon::all()->map(function ($pokemon) {
            return [
                'id' => $pokemon->id,
                'nombre' => $pokemon->nombre,
                'imagen' => url(Storage::url($pokemon->imagen)), // Generar URL completa
            ];
        });

        return response()->json($pokemons);
    }

    // Crear un nuevo Pokémon
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Subir la imagen
        $image = $request->file('imagen');
        $imagePath = $image->store('pokemon', 'public'); // Guardar imagen en storage/public

        // Crear el Pokémon
        $pokemon = Pokemon::create([
            'nombre' => $request->nombre,
            'imagen' => $imagePath,
        ]);

        return response()->json($pokemon, 201); // 201 para éxito en creación
    }

    // Obtener un Pokémon por su ID
    public function show($id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['message' => 'Pokémon not found'], 404);
        }

        return response()->json([
            'id' => $pokemon->id,
            'nombre' => $pokemon->nombre,
            'imagen' => url(Storage::url($pokemon->imagen)),
        ]);
    }

    // Actualizar un Pokémon
    public function update(Request $request, $id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['message' => 'Pokémon not found'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'imagen' => 'sometimes|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Actualizar el nombre si se proporciona
        if ($request->has('nombre')) {
            $pokemon->nombre = $request->nombre;
        }

        // Actualizar la imagen si se proporciona
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior
            if (Storage::exists($pokemon->imagen)) {
                Storage::delete($pokemon->imagen);
            }

            // Subir la nueva imagen
            $image = $request->file('imagen');
            $imagePath = $image->store('pokemon', 'public');
            $pokemon->imagen = $imagePath;
        }

        $pokemon->save();

        return response()->json($pokemon);
    }

    // Eliminar un Pokémon
    public function destroy($id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json(['message' => 'Pokémon not found'], 404);
        }

        // Eliminar la imagen
        if (Storage::exists($pokemon->imagen)) {
            Storage::delete($pokemon->imagen);
        }

        // Eliminar el Pokémon
        $pokemon->delete();

        return response()->json(['message' => 'Pokémon deleted successfully']);
    }
}
