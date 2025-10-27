<?php

use App\Http\Controllers\CitasController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\HorariosDisponiblesController;
use App\Http\Controllers\MedicosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('registrar', [AuthController::class, 'crear']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('admin.auth')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::get('especialidades', [EspecialidadesController::class, 'index']);
    Route::get('especialidades/{id}', [EspecialidadesController::class, 'show']);
    
    Route::get('medicos', [MedicosController::class, 'index']);
    Route::get('medicos/{id}', [MedicosController::class, 'show']);
    
    Route::get('pacientes', [PacientesController::class, 'index']);
    Route::get('pacientes/{id}', [PacientesController::class, 'show']);
    
    Route::get('horarios', [HorariosDisponiblesController::class, 'index']);
    Route::get('horariosDisponiblesPorMedico', [HorariosDisponiblesController::class, 'horariosDisponiblesPorMedico']);
    
    Route::get('citas', [CitasController::class, 'index']);
    Route::get('citas/{id}', [CitasController::class, 'show']);
    Route::get('pacientesConCitas', [PacientesController::class, 'pacientesConCitas']);
    
    Route::post('crearEspecialidades', [EspecialidadesController::class, 'store']);
    Route::put('editarEspecialidades/{id}', [EspecialidadesController::class, 'update']); 
    Route::delete('eliminarEspecialidades/{id}', [EspecialidadesController::class, 'destroy']);
    
    Route::post('crearMedico', [MedicosController::class, 'store']);
    Route::put('editarMedico/{id}', [MedicosController::class, 'update']);
    Route::delete('eliminarMedico/{id}', [MedicosController::class, 'destroy']);
    
    Route::post('crearPacientes', [PacientesController::class, 'store']); 
    Route::put('editarPacientes/{id}', [PacientesController::class, 'update']);  
    Route::delete('eliminarPacientes/{id}', [PacientesController::class, 'destroy']);
    
    Route::post('crearHorarios', [HorariosDisponiblesController::class, 'store']);
    Route::put('editarHorarios/{id}', [HorariosDisponiblesController::class, 'update']);  
    Route::delete('eliminarHorarios/{id}', [HorariosDisponiblesController::class, 'destroy']);
    
    Route::post('crearCitas', [CitasController::class, 'store']);
    Route::put('editarCitas/{id}', [CitasController::class, 'update']);
    Route::delete('eliminarCitas/{id}', [CitasController::class, 'destroy']);
    Route::put('cancelarCitas/{id}', [CitasController::class, 'cancelar']);
    
    Route::get('admin', [AdminController::class, 'index']);
    Route::get('admin/{id}', [AdminController::class, 'show']);
    Route::post('crearAdmin', [AdminController::class, 'store']);
    Route::put('editarAdmin/{id}', [AdminController::class, 'update']);
    Route::delete('eliminarAdmin/{id}', [AdminController::class, 'destroy']);
});

Route::get('citasConMedicos', [CitasController::class, 'citasConMedicos']);
Route::get('citasPendientes', [CitasController::class, 'citasPendientes']);
Route::get('citasCompletadas', [CitasController::class, 'citasCompletadas']);
Route::get('citasPorFecha/{fecha}', [CitasController::class, 'citasPorFecha']);
Route::get('medicosConEspecialidad', [MedicosController::class, 'medicosConEspecialidades']);
Route::get('medicosConHorarios', [MedicosController::class, 'medicosConHorarios']);
Route::get('pacientesPorEPS/{eps}', [PacientesController::class, 'pacientesPorEPS']);
Route::get('contarCitasPaciente/{id}', [PacientesController::class, 'ContarCitasPaciente']);Route::get('EspecialidadesConMedicos', [EspecialidadesController::class, 'especialidadesConMedicos']);
