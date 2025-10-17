<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $admin = $request->user();

        if (!$admin) {
            return response()->json(['message' => 'No se tiene acceso a esta ruta'], 401);
        }

        $allowedRoles = $roles;
        
        if (empty($allowedRoles)) {
            return $next($request);
        }

        // if (!in_array($actualRole, $expectedRoles)) {
        //     return response()->json(['message' => 'NO se puede acceder'], 403);
        // }

        return $next($request);
    }
}