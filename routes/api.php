<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;

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
    Route::post('/activar_cuenta/{id}', [UsuariosController::class, 'activar_cuenta']);
    Route::get('/listar_personas', [UsuariosController::class, 'listar_personas']);
    Route::get('/ver_persona/{id}', [UsuariosController::class, 'ver_persona']);

});
