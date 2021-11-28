<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $hidden = ['updated_at', 'created_at', 'descripcion','pivot','foto'];
    public function videos(){
        return $this -> hasMany(Video::class, 'curso_asociado');
    }

    public function usuarios(){
        return $this -> belongsToMany(Usuario::class,'cursos_usuarios');
    }
}
