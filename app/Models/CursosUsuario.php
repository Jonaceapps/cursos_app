<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursosUsuario extends Model
{
    use HasFactory;

    protected $table = 'cursos_usuarios';
    public function Curso()
    {
        return $this->belongsTo(Curso::class);
    }
    public function Usuario()
    {
        return $this->belongsTo(Usario::class);
    }
}