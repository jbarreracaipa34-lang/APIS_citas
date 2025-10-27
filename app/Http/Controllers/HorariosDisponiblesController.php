<?php

namespace App\Http\Controllers;

use App\Models\HorariosDisponibles;
use App\Models\Citas;
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

        $data = $validator->validated();
        
        $horaInicio = \DateTime::createFromFormat('H:i', $data['horaInicio']);
        $horaFin = \DateTime::createFromFormat('H:i', $data['horaFin']);
        
        if (!$horaInicio || !$horaFin) {
            return response()->json(['error' => 'Formato de hora inválido'], 422);
        }
        
        if ($horaFin <= $horaInicio) {
            return response()->json(['error' => 'La hora de fin debe ser posterior a la hora de inicio'], 422);
        }
        
        $horariosCreados = [];
        $horaActual = clone $horaInicio;
        
        while ($horaActual < $horaFin) {
            $horaSiguiente = clone $horaActual;
            $horaSiguiente->add(new \DateInterval('PT30M'));
            
            if ($horaSiguiente > $horaFin) {
                $horaSiguiente = clone $horaFin;
            }
            
            $horarioData = [
                'medicos_id' => $data['medicos_id'],
                'diaSemana' => $data['diaSemana'],
                'horaInicio' => $horaActual->format('H:i'),
                'horaFin' => $horaSiguiente->format('H:i')
            ];
            
            $horario = HorariosDisponibles::create($horarioData);
            $horariosCreados[] = $horario;
            
            $horaActual->add(new \DateInterval('PT30M'));
        }
        
        return response()->json([
            'message' => 'Horarios creados exitosamente',
            'horarios' => $horariosCreados,
            'total' => count($horariosCreados)
        ], 201);
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

        // Verificar si hay citas asociadas a este horario específico
        try {
            $citasAsociadas = Citas::where('medicos_id', $horario->medicos_id)
                ->where('horaCita', $horario->horaInicio)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->get();

            if ($citasAsociadas->count() > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar el horario porque tiene citas asociadas',
                    'citas_asociadas' => $citasAsociadas->count()
                ], 409);
            }
        } catch (\Exception $e) {
            \Log::error('Error verificando citas asociadas: ' . $e->getMessage());
            // Si hay error en la verificación, permitir la eliminación
        }

        $horario->delete();
        return response()->json(['message' => 'HorarioDisponible eliminado correctamente']);
    }

    public function horariosDisponiblesPorMedico(Request $request) {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        
        if ($user instanceof \App\Models\Medicos) {
            $data = DB::table('horariosdisponibles')
                ->join('medicos', 'horariosdisponibles.medicos_id', '=', 'medicos.id')
                ->where('medicos.id', $user->id)
                ->select('horariosdisponibles.id', 'medicos.nombre', 'medicos.apellido', 'horariosdisponibles.diaSemana', 
                        'horariosdisponibles.horaInicio', 'horariosdisponibles.horaFin', 'horariosdisponibles.medicos_id')
                ->get();
        } else {
            $data = DB::table('horariosdisponibles')
                ->join('medicos', 'horariosdisponibles.medicos_id', '=', 'medicos.id')
                ->select('horariosdisponibles.id', 'medicos.nombre', 'medicos.apellido', 'horariosdisponibles.diaSemana', 
                        'horariosdisponibles.horaInicio', 'horariosdisponibles.horaFin', 'horariosdisponibles.medicos_id')
                ->get();
        }
        
        return response()->json($data, 200);
    }
}