<?php

namespace App\Http\Controllers;

use App\Models\Medicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
            'email'           => 'nullable|string',
            'especialidad_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medico = Medicos::create($validator->validated());
        return response()->json($medico, 200);
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
            'email'           => 'string',
            'especialidad_id' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medico->update($validator->validated());
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
