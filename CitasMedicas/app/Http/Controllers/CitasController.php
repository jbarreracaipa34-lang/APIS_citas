<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    public function index()
    {
        $citas = Citas::all();
        return response()->json($citas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fechaCita' => 'required|date',
            'horaCita' => 'required',
            'estado' => 'required|in:pendiente,completada,cancelada',
            'observaciones' => 'nullable|string',
            'pacientes_id' => 'required|integer',
            'medicos_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cita = Citas::create($validator->validated());
        return response()->json($cita, 200);
    }

    public function show(string $id)
    {
        $cita = Citas::find($id);
        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        return response()->json($cita);
    }

    public function update(Request $request, string $id)
    {
        $cita = Citas::find($id);
        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'fechaCita' => 'date',
            'horaCita' => 'required',
            'estado' => 'required|in:pendiente,completada,cancelada',
            'observaciones' => 'string',
            'pacientes_id' => 'integer',
            'medicos_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cita->update($validator->validated());
        return response()->json($cita);
    }

    public function destroy(string $id)
    {
        $cita = Citas::find($id);
        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $cita->delete();
        return response()->json(['message' => 'Cita eliminada correctamente']);
    }

    public function citasConMedicos(){
        $data = DB::table('citas')->join('medicos', 'citas.medicos_id', '=', 'medicos.id')
        ->select('citas.id', 'citas.fechaCita', 'citas.horaCita', 'medicos.nombre as medico_nombre', 'medicos.apellido as medico_apellido')->get();
        return response()->json($data, 200);
    }

    public function citasPendientes() {
        $data = DB::table('citas')->where('estado', '=', 'pendiente')->select('pacientes_id','fechaCita', 'estado')->get();
        return response()->json($data, 200);
    }

    public function citasCompletadas(){
        $data = DB::table('citas')->where('estado', '=','completada')->select('fechaCita', 'estado')->get();
        return response()->json($data);
    }

    public function citasPorFecha($fecha){
        $data = DB::table('citas')->whereDate('fechaCita', $fecha)->get();
        return response()->json($data);
    }
}