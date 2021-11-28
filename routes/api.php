<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\VideosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('usuarios')->group(function(){

    Route::put('/crear',[UsuariosController::class, 'crear']);
    Route::post('/editar/{id}', [UsuariosController::class, 'editar']);
    Route::post('/desactivar_cuenta/{id}', [UsuariosController::class, 'desactivar_cuenta']);
    Route::put('/adquirir_curso/{id}/{id_curso}', [UsuariosController::class, 'adquirir_curso']);
    Route::get('/ver_cursos/{id}', [UsuariosController::class, 'ver_cursos']);
    Route::get('/ver_videos_curso/{id_usuario}/{id_curso}', [UsuariosController::class, 'ver_videos_curso']);
    Route::get('/ver_video/{id_usuario}/{id_curso}/{id_video}', [UsuariosController::class, 'ver_video']);
});

Route::prefix('cursos')->group(function(){

    Route::put('/alta',[CursosController::class, 'alta']);
    Route::get('/ver_cursos', [CursosController::class, 'ver_cursos']);
});

Route::prefix('videos')->group(function(){

    Route::put('/subir',[VideosController::class, 'subir']);
});
