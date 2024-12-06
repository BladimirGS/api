<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemon';  // Asegúrate de que la tabla sea la correcta
    public $timestamps = true;  // Esto asegura que Laravel gestionará los campos created_at y updated_at

    protected $fillable = ['nombre', 'imagen']; // Lista de campos que se pueden actualizar
}
