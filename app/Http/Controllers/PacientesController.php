<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacientesController extends Controller
{
    public function index()
    {
        $pacientes = Pacientes::all();
        return response()->json($pacientes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'tipoDocumento' => 'required|in:CC,TI,CE',
            'numeroDocumento' => 'required|string',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:M,F',
            'telefono' => 'required|string',
            'email' => 'nullable|string',
            'direccion' => 'nullable|string',
            'eps' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $paciente = Pacientes::create($validator->validated());
        return response()->json($paciente);
    }

    public function show(string $id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        return response()->json($paciente);
    }

    public function update(Request $request, string $id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string',
            'apellido' => 'string',
            'tipoDocumento' => 'in:CC,TI,CE',
            'numeroDocumento' => 'string',
            'fechaNacimiento' => 'date',
            'genero' => 'in:M,F',
            'telefono' => 'string',
            'email' => 'nullable|string',
            'direccion' => 'nullable|string',
            'eps' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $paciente->update($validator->validated());
        return response()->json($paciente);
    }

    public function destroy(string $id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        $paciente->delete();
        return response()->json(['message' => 'Paciente eliminado correctamente']);
    }

    public function pacientesConCitas(){
        $data = DB::table('pacientes')->join('citas', 'pacientes.id', '=', 'citas.pacientes_id')
        ->select('pacientes.id', 'pacientes.nombre', 'pacientes.apellido', 'citas.fechaCita', 'citas.horaCita')->get();
        return response()->json($data, 200);

    }

    public function pacientesPorEPS($eps) {
        $data = DB::table('pacientes')->where('eps', $eps)->select('nombre', 'apellido', 'eps')->get();
        return response()->json($data, 200);
    }

    public function ContarCitasPaciente($id){
    $data = DB::table('citas')->where('pacientes_id', '=', $id)->count();
    return response()->json(['paciente_id' => $id, 'totalCitas' => $data]);
    }
}