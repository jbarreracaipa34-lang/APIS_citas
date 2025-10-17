<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use App\Models\Pacientes;
use App\Models\Medicos;
use Laravel\Sanctum\PersonalAccessToken;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        
        if (!$token) {
            return response()->json(['message' => 'Token no proporcionado'], 401);
        }

        $token = str_replace('Bearer ', '', $token);
        
        $accessToken = PersonalAccessToken::findToken($token);
        
        if (!$accessToken) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $user = $accessToken->tokenable;
        
        if (!$user || !($user instanceof Admin || $user instanceof Pacientes || $user instanceof Medicos)) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $userType = 'unknown';
        if ($user instanceof Admin) {
            $userType = 'admin';
        } elseif ($user instanceof Pacientes) {
            $userType = 'paciente';
        } elseif ($user instanceof Medicos) {
            $userType = 'medico';
        }

        $request->merge(['user' => $user, 'user_type' => $userType]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}