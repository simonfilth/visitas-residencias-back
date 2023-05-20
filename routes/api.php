<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApartamentosController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VisitasController;
use App\Http\Controllers\TorresController;
use App\Http\Controllers\ResidenciasController;
use App\Http\Controllers\PropietariosController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthController::class, 'getUserAuth']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::put('/usuario/anular/{id}', [UsersController::class, 'anular']);
    Route::post('/usuario/verificar-pin/{id}', [UsersController::class, 'verificarPin']);

    Route::post('/residencia/guardar', [ResidenciasController::class, 'guardar']);
    Route::get('/residencias/get', [ResidenciasController::class, 'getAll']);
    Route::get('/residencia/get/{id}', [ResidenciasController::class, 'get']);
    Route::put('/residencia/actualizar/{id}', [ResidenciasController::class, 'actualizar']);
    Route::delete('/residencia/eliminar/{id}', [ResidenciasController::class, 'eliminar']);
    Route::get('/residencia/cargar-datos/{id}', [ResidenciasController::class, 'cargarDatos']);

    Route::post('/torre/guardar/{id}', [TorresController::class, 'guardar']);
    Route::get('/torres/get/{id}', [TorresController::class, 'getAll']);
    Route::get('/torre/get/{id}', [TorresController::class, 'get']);
    Route::post('/torre/actualizar/{id}', [TorresController::class, 'actualizar']);
    Route::get('/torre/cargar-datos/{id}', [TorresController::class, 'cargarDatos']);

    // Route::get('/torre/cargar-apartamentos/{id}', [TorresController::class, 'cargarApartamentos']);
    Route::delete('/torre/eliminar/{id}', [TorresController::class, 'eliminar']);

    Route::post('/apartamento/guardar', [ApartamentosController::class, 'guardar']);
    Route::get('/apartamentos/get/{id}', [ApartamentosController::class, 'getAll']);
    Route::get('/residencia/apartamentos/get/{id}', [ApartamentosController::class, 'getAllByUser']);
    Route::get('/apartamento/get/{id}', [ApartamentosController::class, 'get']);
    Route::post('/apartamento/actualizar/{id}', [ApartamentosController::class, 'actualizar']);
    Route::delete('/apartamento/eliminar/{id}', [ApartamentosController::class, 'eliminar']);
    Route::get('/apartamento/cargar-propietarios/{id}', [ApartamentosController::class, 'cargarPropietarios']);

    Route::post('/propietario/guardar', [PropietariosController::class, 'guardar']);
    Route::get('/propietarios/get/{id}', [PropietariosController::class, 'getAll']);
    Route::get('/residencia/propietarios/get/{id}', [PropietariosController::class, 'getAllByUser']);
    Route::get('/propietario/get/{id}', [PropietariosController::class, 'get']);
    Route::post('/propietario/actualizar/{id}', [PropietariosController::class, 'actualizar']);
    Route::delete('/propietario/eliminar/{id}', [PropietariosController::class, 'eliminar']);

    Route::post('/visita/guardar/{id}', [VisitasController::class, 'guardar']);
    Route::delete('/visita/eliminar/{id}', [VisitasController::class, 'eliminar']);
    Route::get('/visitas/get/{id}', [VisitasController::class, 'getByResidencia']);
    Route::get('/visitas/exportar-excel/{id}', [VisitasController::class, 'exportarExcel']);

});

Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);

// Route::post('/test-correo', [VisitasController::class, 'testCorreo']);
