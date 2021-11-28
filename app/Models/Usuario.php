<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $hidden = ['updated_at', 'created_at', 'pass', 'activo','foto'];
    public function cursos(){
    return $this->belongsToMany(Curso::class,'cursos_usuarios');
    }
    
   
}
