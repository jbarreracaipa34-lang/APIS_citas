<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspecialidadesController extends Controller
{
    public function index()
    {
        $especialidades = Especialidades::all();
        return response()->json($especialidades);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $especialidad = Especialidades::create($validator->validated());
        return response()->json($especialidad, 200);
    }

    public function show(string $id)
    {
        $especialidad = Especialidades::find($id);
        if (!$especialidad) {
            return response()->json(['message' => 'Especialidad no encontrada'], 404);
        }
        
        return response()->json($especialidad);
    }

    public function update(Request $request, string $id)
    {
        $especialidad = Especialidades::find($id);

        if (!$especialidad) {
            return response()->json(['message' => 'Especialidad no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string',
            'descripcion' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $especialidad->update($validator->validated());
        return response()->json($especialidad);
    }

    public function destroy(string $id)
    {
        $especialidad = Especialidades::find($id);
        if (!$especialidad) {
            return response()->json(['message' => 'Especialidad no encontrada'], 404);
        }

        $especialidad->delete();
        return response()->json(['message' => 'Especialidad eliminada correctamente']);
    }
}