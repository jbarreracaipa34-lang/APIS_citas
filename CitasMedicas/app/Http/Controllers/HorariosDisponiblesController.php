<?php

namespace App\Http\Controllers;

use App\Models\HorariosDisponibles;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorariosDisponiblesController extends Controller
{
    public function index()
    {
        $horarios = HorariosDisponibles::all();
        return response()->json($horarios);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medicos_id'   => 'required|integer',
            'diaSemana'   => 'required|in:L,Mar,Mie,J,V,S',
            'horaInicio' => 'required',
            'horaFin'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $horario = HorariosDisponibles::create($validator->validated());
        return response()->json($horario, 201);
    }

    public function show(string $id)
    {
        $horario = HorariosDisponibles::find($id);
        if (!$horario) {
            return response()->json(['message' => 'HorarioDisponible no encontrado'], 404);
        }

        return response()->json($horario);
    }

    public function update(Request $request, string $id)
    {
        $horario = HorariosDisponibles::find($id);

        if (!$horario) {
            return response()->json(['message' => 'HorarioDisponible no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'medicos_id'   => 'integer',
            'diaSemana'   => 'in:L,Mar,Mie,J,V,S',
            'horaInicio' => 'required',
            'horaFin'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $horario->update($validator->validated());
        return response()->json($horario);
    }

    public function destroy(string $id)
    {
        $horario = HorariosDisponibles::find($id);
        if (!$horario) {
            return response()->json(['message' => 'HorarioDisponible no encontrado'], 404);
        }

        $horario->delete();
        return response()->json(['message' => 'HorarioDisponible eliminado correctamente']);
    }

    public function horariosDisponiblesPorMedico() {
    $data = DB::table('horariosdisponibles')->join('medicos', 'horariosdisponibles.medicos_id', '=', 'medicos.id')
    ->select('horariosdisponibles.id','medicos.nombre', 'medicos.apellido', 'horariosdisponibles.diaSemana', 
    'horariosdisponibles.horaInicio', 'horariosdisponibles.horaFin')->get();
    return response()->json($data, 200);
    }
}