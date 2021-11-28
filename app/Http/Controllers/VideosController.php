<?php

namespace App\Http\Controllers;
use App\Models\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
     // 3. "parte 2"
     // Por cada curso se deben poder asociar una serie de vídeos, que tendrán título, foto de portada y enlace al vídeo.
    public function subir(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req -> getContent();
        $datos = json_decode($datos); 
        
        $video = new Video();

        $video -> titulo = $datos->titulo;
        $video -> foto_portada = $datos->foto_portada;
        $video -> enlace = $datos->enlace;
        //Aqui se asocia un video a un curso.
        $video -> curso_asociado = $datos->curso_asociado;

        try {
            $video->save();
            $respuesta["msg"] = "Video Guardado";
        }catch (\Exception $e) {
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
        }  
           
        return response()->json($respuesta);
    }
}
