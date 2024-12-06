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
        // Valida que el nombre y la imagen sean proporcionados
        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Asegúrate de aceptar imágenes
        ]);

        // Obtiene la imagen y la guarda
        $image = $request->file('imagen');
        $imagePath = $image->store('pokemons', 'public'); // Guardará en 'storage/app/public/pokemons'

        // Crea el nuevo Pokémon
        $pokemon = Pokemon::create([
            'nombre' => $request->input('nombre'),
            'imagen' => $imagePath, // Almacena la ruta de la imagen
        ]);

        return response()->json([
            'status' => 'success',
            'pokemon' => $pokemon,
        ], 201);
    }

    public function update(Request $request, $id)
    {
     // Encuentra el Pokémon a actualizar
     $pokemon = Pokemon::findOrFail($id);

     // Valida los datos
     $request->validate([
         'nombre' => 'required|string|max:255',
         'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
     ]);
 
     // Actualiza el nombre
     $pokemon->nombre = $request->input('nombre');
 
     // Si hay una nueva imagen, reemplaza la existente
     if ($request->hasFile('imagen')) {
         $image = $request->file('imagen');
         $imagePath = $image->store('pokemons', 'public'); // Guardar en 'storage/app/public/pokemons'
 
         // Elimina la imagen antigua si existe
         if ($pokemon->imagen) {
             Storage::disk('public')->delete($pokemon->imagen);
         }
 
         // Actualiza la ruta de la imagen
         $pokemon->imagen = $imagePath;
     }
 
     // Guarda los cambios
     $pokemon->save();
 
     return response()->json([
         'status' => 'success',
         'pokemon' => $pokemon,
     ], 200);
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
