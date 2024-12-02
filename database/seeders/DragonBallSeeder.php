<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DragonBall;

class DragonBallSeeder extends Seeder
{
    public function run()
    {
        $characters = [
            ['nombre' => 'Goku', 'imagen' => 'https://example.com/goku.jpg'],
            ['nombre' => 'Vegeta', 'imagen' => 'https://example.com/vegeta.jpg'],
            ['nombre' => 'Piccolo', 'imagen' => 'https://example.com/piccolo.jpg'],
            ['nombre' => 'Gohan', 'imagen' => 'https://example.com/gohan.jpg'],
        ];

        foreach ($characters as $character) {
            DragonBall::create($character);
        }
    }
}
