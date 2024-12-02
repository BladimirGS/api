<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;

class FetchPokemon extends Command
{
    protected $signature = 'fetch:pokemon';
    protected $description = 'Fetch Pokémon data and save it locally';

    public function handle()
    {
        // Obtener datos de la API de Pokémon
        $response = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon?limit=30'), true);

        if (!$response || !isset($response['results'])) {
            $this->error('Error fetching Pokémon data.');
            return 1;
        }

        foreach ($response['results'] as $index => $pokemon) {
            $id = $index + 1; // Generar ID basado en el índice
            $name = $pokemon['name'];
            $imageUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png";

            try {
                // Descargar contenido de la imagen
                $imageContents = file_get_contents($imageUrl);

                // Guardar la imagen en storage/app/public/pokemon
                $imagePath = "pokemon/{$id}.png"; // Ruta relativa dentro de storage/app/public
                Storage::disk('public')->put($imagePath, $imageContents);

                // Guardar la información del Pokémon en la base de datos
                Pokemon::updateOrCreate(
                    ['nombre' => $name], // Evitar duplicados si ya existe por nombre
                    ['imagen' => $imagePath] // Guardar la ruta relativa
                );
            } catch (\Exception $e) {
                $this->error("Failed to save Pokémon: {$name}. Error: {$e->getMessage()}");
                continue;
            }
        }

        $this->info('Pokémon data fetched and saved successfully.');
        return 0;
    }
}
