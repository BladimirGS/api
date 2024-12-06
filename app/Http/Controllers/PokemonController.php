<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    // Actualizar un Pokémon existente
    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'string',
        ]);
    
        // Encontrar el Pokémon por ID
        $pokemon = Pokemon::findOrFail($id);
    
        // Actualizar los datos
        $pokemon->nombre = $request->input('nombre');
    
        // Si hay una imagen, manejar la carga
        $pokemon->imagen = "pokemon/" . $request->input('imagen');
    
        // Guardar los cambios
        $pokemon->save();
    
        // Devolver la respuesta con el Pokémon actualizado
        return response()->json($request, 200);
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
