<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemon';  
    public $timestamps = true; 

    protected $fillable = ['nombre', 'imagen']; 
}
