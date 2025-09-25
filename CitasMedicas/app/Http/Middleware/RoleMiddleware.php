<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No se tiene acceso a esta ruta'], 401);
        }

        $allowedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, explode(',', $role));
            } else {
                $allowedRoles[] = $role;
            }
        }

        $actualRole = trim(strtolower($user->role));
        $expectedRoles = array_map(fn($r) => trim(strtolower($r)), $allowedRoles);

        

        if (!in_array($actualRole, $expectedRoles)) {
            return response()->json([
                'message' => 'NO se puede acceder',
                'debug' => [
                    'user_role' => $actualRole,
                    'allowed_roles' => $expectedRoles
                ]
            ], 403);
        }

        return $next($request);
    }
}