<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Pacientes;
use App\Models\Medicos;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'user_type' => 'required|in:admin,medico,paciente'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userType = $request->user_type;
        $user = null;
        $role = null;

        switch ($userType) {
            case 'admin':
                $user = Admin::where('email', $request->email)->first();
                $role = 'admin';
                break;
            case 'medico':
                $user = Medicos::where('email', $request->email)->first();
                $role = 'medico';
                break;
            case 'paciente':
                $user = Pacientes::where('email', $request->email)->first();
                $role = 'paciente';
                break;
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken($role . '-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token,
            'role' => $role,
            'success' => true
        ], 200);
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
            
            $role = 'unknown';
            if ($user instanceof Admin) {
                $role = 'admin';
            } elseif ($user instanceof Pacientes) {
                $role = 'paciente';
            } elseif ($user instanceof Medicos) {
                $role = 'medico';
                $user->load('especialidad');
            }
            
            return response()->json([
                'message' => 'Usuario autenticado correctamente',
                'user' => $user,
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function crear(Request $request)
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
}