<?php

namespace App\Http\Controllers;
use App\Models\Usuario;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function crear(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req -> getContent();

        //VALIDAR EL JSON 
        $datos = json_decode($datos); //Se puede pasar un parametro para que en su lugar lo devuelva como array
        
        //VALIDAR LOS DATOS    
        $usuario = new Usuario();

        $usuario -> nombre = $datos->nombre;
        $usuario -> email = $datos->email;
        $usuario -> pass = $datos->pass;
        $usuario -> foto = $datos->foto;
      
        //Escribir en la base de datos
        try {
            $usuario->save();
            $respuesta["msg"] = "Usuario Guardado";
        }catch (\Exception $e) {
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
        }
        return response()->json($respuesta);
    }

    public function editar(Request $req, $id){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req -> getContent();

        //VALIDAR EL JSON 
        $datos = json_decode($datos); //Se puede pasar un parametro para que en su lugar lo devuelva como array
        //Buscar usuario
        $usuario = Usuario::find($id);

        if ($usuario){

            if(isset($datos->nombre))
            $usuario -> nombre = $datos->nombre;

            if(isset($datos->foto))
            $usuario -> foto = $datos->foto;

            if(isset($datos->pass))
            $usuario -> pass = $datos->pass;


            //Escribir en la base de datos
            try {
                if(isset($datos->email)){
                    $respuesta["msg"] = "El email no se puede cambiar"; 
                    $respuesta["status"] = 0;
                } else {
                    $usuario->save();
                    $respuesta["msg"] = "Cambios realizados.";
                }
            }catch (\Exception $e) {
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            }
        } else {
                $respuesta["msg"] = "usuario no encontarda"; 
                $respuesta["status"] = 0;
        }
        return response()->json($respuesta);
    }

    public function desactivar_cuenta($id){

        $respuesta = ["status" => 1, "msg" => ""];
        //Buscar a el usuario
        $usuario = Usuario::find($id);

        if($usuario && $usuario -> activo == true){
            try {
                $usuario -> activo = false;
                $usuario->save();
                $respuesta["msg"] = "Usuario desactivado";
                
            }catch (\Exception $e) {
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            }
        }else {
            $respuesta["msg"] = "Usuario no encontrado"; 
            $respuesta["status"] = 0;
        }

        return response()->json($respuesta);

    }
}
