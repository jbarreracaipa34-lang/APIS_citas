<?php

use App\Http\Controllers\CitasController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\HorariosDisponiblesController;
use App\Http\Controllers\MedicosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('registrar' , [AuthController::class, 'crear']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('especialidades', [EspecialidadesController::class, 'index']);
    Route::get('especialidades/{id}', [EspecialidadesController::class, 'show']);
    
    Route::get('medicos', [MedicosController::class, 'index']);
    Route::get('medicos/{id}', [MedicosController::class, 'show']);
    
    Route::get('pacientes', [PacientesController::class, 'index']);
    Route::get('pacientes/{id}', [PacientesController::class, 'show']);
    
    Route::get('horarios', [HorariosDisponiblesController::class, 'index']);
    
    Route::get('citas', [CitasController::class, 'index']);
    Route::post('crearCitas', [CitasController::class, 'store']);
    Route::get('citas/{id}', [CitasController::class, 'show']);
    Route::put('editarCitas/{id}', [CitasController::class, 'update']);
    Route::delete('eliminarCitas/{id}', [CitasController::class, 'destroy']);
    
    Route::get('pacientesConCitas', [PacientesController::class, 'pacientesConCitas']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('crearEspecialidades', [EspecialidadesController::class, 'store']);
    Route::put('editarEspecialidades/{id}', [EspecialidadesController::class, 'update']); 
    Route::delete('eliminarEspecialidades/{id}', [EspecialidadesController::class, 'destroy']);
    
    Route::post('crearHorarios', [HorariosDisponiblesController::class, 'store']);
    Route::put('editarHorarios/{id}', [HorariosDisponiblesController::class, 'update']);  
    Route::delete('eliminarHorarios/{id}', [HorariosDisponiblesController::class, 'destroy']);
    
    Route::post('crearMedico', [MedicosController::class, 'store']);
    Route::put('editarMedico/{id}', [MedicosController::class, 'update']);
    Route::delete('eliminarMedico/{id}', [MedicosController::class, 'destroy']);
    
    Route::post('crearPacientes', [PacientesController::class, 'store']); 
    Route::put('editarPacientes/{id}', [PacientesController::class, 'update']);  
    Route::delete('eliminarPacientes/{id}', [PacientesController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:admin,medico'])->group(function () {
    Route::post('registrarPacienteConUserId', [PacientesController::class, 'RegistrarPacienteConUserId']);
});

Route::middleware(['auth:sanctum', 'role:medico'])->group(function () {
    Route::post('crearEspecialidades', [EspecialidadesController::class, 'store']);
    Route::put('editarEspecialidades/{id}', [EspecialidadesController::class, 'update']);
    Route::delete('eliminarEspecialidades/{id}', [EspecialidadesController::class, 'destroy']);
    Route::post('crearHorarios', [HorariosDisponiblesController::class, 'store']);
    Route::put('editarHorarios/{id}', [HorariosDisponiblesController::class, 'update']);
    Route::delete('eliminarHorarios/{id}', [HorariosDisponiblesController::class, 'destroy']);
});

Route::get('citasConMedicos', [CitasController::class, 'citasConMedicos']);
Route::get('citasPendientes', [CitasController::class, 'citasPendientes']);
Route::get('citasCompletadas', [CitasController::class, 'citasCompletadas']);
Route::get('citasPorFecha/{fecha}', [CitasController::class, 'citasPorFecha']);
Route::get('horariosDisponiblesPorMedico', [HorariosDisponiblesController::class, 'horariosDisponiblesPorMedico']);
Route::get('medicosConEspecialidad', [MedicosController::class, 'medicosConEspecialidades']);
Route::get('medicosConHorarios', [MedicosController::class, 'medicosConHorarios']);
Route::get('pacientesPorEPS/{eps}', [PacientesController::class, 'pacientesPorEPS']);
Route::get('contarCitasPaciente/{id}', [PacientesController::class, 'ContarCitasPaciente']);
Route::get('EspecialidadesConMedicos', [EspecialidadesController::class, 'especialidadesConMedicos']);