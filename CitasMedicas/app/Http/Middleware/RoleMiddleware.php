<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No se tiene acceso a esta ruta'], 401);
        }

        $actualRole = trim(strtolower($user->role));
        $expectedRoles = array_map(fn($r) => trim(strtolower($r)), $roles);

        if (!in_array($actualRole, $expectedRoles)) {
            \Log::warning('Acceso denegado por rol', [
                'usuario_id' => $user->id,
                'rol_usuario' => $user->role,
                'roles_permitidos' => $roles
            ]);
            return response()->json(['message' => 'NO se puede acceder'], 403);
        }

        return $next($request);
    }
}
