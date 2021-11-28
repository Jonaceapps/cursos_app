<?php

namespace App\Http\Controllers;
use App\Models\Curso;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CursosController extends Controller
{
    // 3. Debemos poder dar de alta cursos. 
    // Un curso tiene un título, una descripción y una foto. 
    // (En Videos Controller) Por cada curso se deben poder asociar una serie de vídeos, que tendrán título, foto de portada y enlace al vídeo.
    public function alta(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req -> getContent();
        $datos = json_decode($datos); 
          
        $curso = new Curso();

        $curso -> titulo = $datos->titulo;
        $curso -> descripcion = $datos->descripcion;
        $curso -> foto = $datos->foto;

        try {
            $curso->save();
            $respuesta["msg"] = "Curso Guardado";
        }catch (\Exception $e) {
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
        }     
        return response()->json($respuesta);
    }

    // 4. Debe existir la opción de listar todos los cursos que tenemos registrados. 
    // El listado muestra los títulos de los cursos, su foto y la cantidad total de vídeos que tiene. Este listado es filtrable por títulos (obteniendo todos aquellos en cuyo título aparezca la palabra buscada).
    public function ver_cursos(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        try {
            //Ver cursos por titulo
            if($req -> has('titulo')){
               $cursos = Curso::withCount('videos as videos_asociados')
               ->where('cursos.titulo','like','%'. $req -> input('titulo').'%')
               ->get();

            //Ver todos los cursos si no se pasa ningun titulo.
            } else {
                $cursos = Curso::withCount('videos as videos_asociados')
                ->get();  
            }

            $respuesta['cursos'] = $cursos;

        }catch (\Exception $e) {
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
        }
        return response()->json($respuesta);

    }
}
