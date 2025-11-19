<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\FincaController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('departamentos', DepartamentoController::class)->middleware('rol:admin,empleado,productor');
    Route::apiResource('ciudades', CiudadController::class)->middleware('rol:admin,empleado,productor');
    Route::apiResource('fincas', FincaController::class)->middleware('rol:admin,empleado,productor');
    Route::apiResource('lotes', LoteController::class)->middleware('rol:admin,empleado,productor');
    Route::apiResource('recepciones', RecepcionController::class)->middleware('rol:admin,empleado');
    Route::apiResource('envios', EnvioController::class)->middleware('rol:admin,productor');
    Route::apiResource('usuarios', UserController::class);
    Route::get('/mis-recepciones', [RecepcionController::class, 'misRecepciones'])
        ->middleware('rol:empleado,admin');
    Route::get('/mis-fincas', [FincaController::class, 'misFincas'])
        ->middleware('rol:productor,admin');
    Route::get('/lotes-finca/{idFinca}', [LoteController::class, 'lotesFinca'])
        ->middleware('rol:productor,admin');
    Route::get('/ciudades-departamento/{idDepartamento}', [CiudadController::class, 'ciudadesDepartamento'])
        ->middleware('rol:productor,admin');
    Route::get('/mis-envios', [EnvioController::class, 'misEnvios'])
        ->middleware('rol:productor,admin');
    Route::patch('/cambiar-estado-envio/{id}/{estado}', [EnvioController::class, 'updateStatus'])
        ->middleware('rol:admin,productor');
    Route::patch('/usuarios/actualizarRol/{id}', [UserController::class, 'actualizarRol'])
        ->middleware('rol:admin');

    Route::post('/logout', [AuthController::class, 'logout']);
});
