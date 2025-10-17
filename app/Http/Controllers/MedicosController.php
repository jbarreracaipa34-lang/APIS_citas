<?php

namespace App\Http\Controllers;

use App\Models\Medicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MedicosController extends Controller
{
    public function index()
    {
        $medicos = Medicos::all();
        return response()->json($medicos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'         => 'required|string',
            'apellido'        => 'required|string',
            'numeroLicencia'  => 'required|string',
            'telefono'        => 'required|string',
            'email'           => 'required|email|unique:medicos,email',
            'especialidad_id' => 'required|integer',
            'password'         => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $medico = Medicos::create($data);
        return response()->json([
            'message' => 'Médico creado exitosamente',
            'medico' => $medico,
            'success' => true
        ], 201);
    }

    public function show(string $id)
    {
        $medico = Medicos::find($id);

        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        return response()->json($medico);
    }

    public function update(Request $request, string $id)
    {
        $medico = Medicos::find($id);

        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'         => 'string',
            'apellido'        => 'string',
            'numeroLicencia'  => 'string',
            'telefono'        => 'string',
            'email'           => 'email|unique:medicos,email,' . $id,
            'especialidad_id' => 'integer',
            'password'         => 'sometimes|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $medico->update($data);
        return response()->json($medico);
    }

    public function destroy(string $id)
    {
        $medico = Medicos::find($id);

        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        $medico->delete();

        return response()->json(['message' => 'Medico eliminado correctamente']);
    }

    public function medicosConEspecialidades()
    {
        $medicos = DB::table('medicos')
            ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
            ->select('medicos.id', 'medicos.nombre', 'medicos.apellido', 'medicos.numeroLicencia',
                'medicos.telefono', 'medicos.email', 'medicos.especialidad_id',
                'especialidades.nombre as especialidad_nombre')->get();
        foreach ($medicos as $medico) {
            $horarios = DB::table('horariosdisponibles')
                ->where('medicos_id', $medico->id)
                ->select('diaSemana', 'horaInicio', 'horaFin')
                ->get();
            $medico->horarios_disponibles = $horarios;
        }
        return response()->json($medicos, 200);
    }



    public function medicosConHorarios(){
        $data = DB::table('medicos')->join('horariosdisponibles', 'medicos.id', '=', 'horariosdisponibles.medicos_id')
        ->select('medicos.id','medicos.nombre','medicos.apellido','horariosdisponibles.horaInicio', 'horariosdisponibles.horaFin')->get();
        return response()->json($data, 200);
    }
}
