<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $hidden = ['updated_at', 'created_at','visto'];
    public function curso(){
        return $this->belongsTo(Curso::class);
    }
}
