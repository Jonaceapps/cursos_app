<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Video;
use App\Models\CursosUsuario;//borrar si no va
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    //1. Los usuarios podrán registrarse con nombre, foto, email y contraseña. 
    //El email no se puede repetir entre varios usuarios.

    public function crear(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req -> getContent();
        $datos = json_decode($datos); 
         
        $usuario = new Usuario();

        $usuario -> nombre = $datos->nombre;
        $usuario -> pass = $datos->pass;
        $usuario -> foto = $datos->foto;

        if (Usuario::where('email', $datos->email)->first()){
            $respuesta["status"] = 0;
            $respuesta["msg"] = "El email ya existe";
        } else {
            $usuario -> email = $datos->email;
            try {
                $usuario->save();
                $respuesta["msg"] = "Usuario Guardado";
            }catch (\Exception $e) {
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            }
        }
        return response()->json($respuesta);
    }

    // 2. Se debe poder editar la información de los usuarios, excepto el email. 
    //Debe existir una opción para desactivar los usuarios, ya que no se pueden borrar completamente.

    public function editar(Request $req, $id){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req -> getContent();
        $datos = json_decode($datos); 
      
        $usuario = Usuario::find($id);

        if ($usuario){

            if(isset($datos->nombre))
            $usuario -> nombre = $datos->nombre;

            if(isset($datos->foto))
            $usuario -> foto = $datos->foto;

            if(isset($datos->pass))
            $usuario -> pass = $datos->pass;

            if(isset($datos->email)){
                $respuesta["msg"] = "El email no se puede cambiar"; 
                $respuesta["status"] = 0;
            } else {

                try {
                    $usuario->save();
                    $respuesta["msg"] = "Cambios realizados.";
                }catch (\Exception $e) {
                    $respuesta["status"] = 0;
                    $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
                }
            }
            
        } else {
                $respuesta["msg"] = "Usuario no encontrado"; 
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
    // 5. Los usuarios podrán adquirir cursos. Cuando se registre esta solicitud 
    // de adquisición, el curso queda asociado al usuario de forma indefinida.
    public function adquirir_curso($id,$id_curso){

        $respuesta = ["status" => 1, "msg" => ""];

        $usuario = Usuario::find($id);
        $curso = Curso::find($id_curso);
        
        if ($usuario && $curso){

            $usuario->cursos()->attach($curso);
            $respuesta["msg"] = "Curso asociado al usuario";
           
        } else {
            $respuesta["msg"] = "Usuario o curso no encontrado"; 
            $respuesta["status"] = 0;
        }

         return response()->json($respuesta);

        }
    
    // 6. Los usuarios deberán poder ver un listado de cursos específico 
    //    que muestre únicamente aquellos que han adquirido.
    public function ver_cursos($id){

        $respuesta = ["status" => 1, "msg" => ""];

        $usuario = Usuario::find($id);

        if ($usuario){
            $usuario ->cursos; 
            try {       
                if (!$usuario ->cursos ->isEmpty())
                    $respuesta['usuario'] = $usuario;
                else 
                    $respuesta["msg"] = "El usuario no tiene cursos";

            }catch (\Exception $e) {
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            }
        } else {
            $respuesta["msg"] = "Usuario no encontrado"; 
            $respuesta["status"] = 0;
        }
        return response()->json($respuesta);

    }
    // 7. Los usuarios pueden obtener el listado de vídeos de un curso, con su nombre y foto, y un indicador de si ya ha visto el vídeo o no. Esta información solo estará disponible si han adquirido el curso previamente. 
    // Cualquier intento de obtener estos datos sin haber comprado el curso debe devolver un error.
    public function ver_videos_curso($id_usuario,$id_curso){

        $respuesta = ["status" => 1, "msg" => ""];

        $usuario = Usuario::find($id_usuario);
        $curso = Curso::find($id_curso);

        if ($usuario && $curso){

            try {

                $cursos = DB::table('usuarios')
                ->join('cursos_usuarios','cursos_usuarios.usuario_id','usuarios.id')
                ->join('cursos','cursos.id','cursos_usuarios.curso_id')
                ->join('videos','videos.curso_asociado','cursos_usuarios.curso_id')
                ->where('cursos_usuarios.curso_id', $id_curso)
                ->where('cursos_usuarios.usuario_id', $id_usuario)
                ->select('videos.id','videos.titulo','videos.foto_portada','videos.visto')
                ->get(); 

                if(!$cursos->isEmpty())
                $respuesta['videos_curso'] = $cursos;
                else 
                $respuesta["msg"] = "Este usuario no dispone del curso solicitado";
                

            }catch (\Exception $e) {
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            }

        } else {
            $respuesta["msg"] = "Usuario o curso no encontrado"; 
            $respuesta["status"] = 0;
        }
        return response()->json($respuesta);

    }

    public function ver_video($id_usuario,$id_curso,$id_video){

        $respuesta = ["status" => 1, "msg" => ""];

        $usuario = Usuario::find($id_usuario);
        $curso = Curso::find($id_curso);
        $video = Video::find($id_video);

        if ($usuario && $curso && $video){

            try {

                $cursos = DB::table('usuarios')
                ->join('cursos_usuarios','cursos_usuarios.usuario_id','usuarios.id')
                ->join('cursos','cursos.id','cursos_usuarios.curso_id')
                ->join('videos','videos.curso_asociado','cursos_usuarios.curso_id')
                ->where('cursos_usuarios.curso_id', $id_curso)
                ->where('cursos_usuarios.usuario_id', $id_usuario)
                ->where('videos.id', $id_video)
                ->get(); 
                
                if(!$cursos->isEmpty()){
                    
                    $video -> visto = true;
                    $video ->save();
                    $video -> makeVisible(['visto']);
                    $video -> makehidden(['titulo','foto_portada','curso_asociado']);
                    $respuesta['enlaces_videos'] = $video;

                }else{
                    $respuesta["msg"] = "Este usuario no dispone del curso solicitado o el video no ha sido encontrado";
                }
                

            }catch (\Exception $e) {
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            }

        } else {
            $respuesta["msg"] = "Usuario, curso o video no encontrado"; 
            $respuesta["status"] = 0;
        }
        return response()->json($respuesta);

    }
    
}
