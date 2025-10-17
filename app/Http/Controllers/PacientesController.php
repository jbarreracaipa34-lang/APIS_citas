<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PacientesController extends Controller
{
    public function index()
    {
        $pacientes = Pacientes::all();
        return response()->json($pacientes);
    }

    public function registrarPaciente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tipoDocumento' => 'required|in:CC,TI,CE',
            'numeroDocumento' => 'required|string|unique:pacientes,numeroDocumento',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:M,F',
            'telefono' => 'required|string',
            'email' => 'required|email|unique:pacientes,email',
            'direccion' => 'nullable|string',
            'eps' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $paciente = Pacientes::create($data);
        $token = $paciente->createToken('paciente-token')->plainTextToken;

        return response()->json([
            'message' => 'Paciente registrado exitosamente',
            'paciente' => $paciente,
            'token' => $token,
            'success' => true
        ], 201);
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
            'direccion' => 'string',
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
            'direccion' => 'string',
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
        try {
            DB::beginTransaction();
            
            $paciente = Pacientes::find($id);
            if (!$paciente) {
                DB::rollBack();
                return response()->json(['message' => 'Paciente no encontrado'], 404);
            }
            
            $paciente->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Paciente eliminado correctamente',
                'success' => true
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                return response()->json([
                    'message' => 'No se puede eliminar el paciente porque tiene citas asociadas. Elimina primero las citas.'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Error al eliminar el paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pacientesConCitas(Request $request) {
        try {
            $query = DB::table('pacientes')
                ->join('citas', 'pacientes.id', '=', 'citas.pacientes_id')
                ->select(
                    'pacientes.id', 'pacientes.nombre', 'pacientes.apellido', 'pacientes.numeroDocumento as documento',
                    'pacientes.telefono', 'pacientes.email', 'pacientes.fechaNacimiento', 'pacientes.genero', 'pacientes.eps', 
                    'citas.id as cita_id', 'citas.fechaCita', 'citas.horaCita', 'citas.estado', 'citas.medicos_id'
                );
            
            $data = $query->orderByRaw("CASE WHEN estado = 'pendiente' THEN 0 ELSE 1 END")
                ->orderBy('citas.fechaCita', 'DESC')
                ->get();
                
            return response()->json($data, 200);
        } catch (\Exception $e) {
            \Log::error('Error en pacientesConCitas: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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