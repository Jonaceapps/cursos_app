<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    /*public function usuarios(){
    return $this->belongsToMany(Usuario::class, 'cursos_usuarios', 'id', 'id');
    }*/
    
}
