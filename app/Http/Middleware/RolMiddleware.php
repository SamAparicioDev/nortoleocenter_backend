<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Obtener el usuario autenticado (asumiendo 'auth:sanctum' o similar ya se ejecutó)
        $user = $request->user();

        // 2. Verificar autenticación
        if (!$user) {
            // Error 401 si no hay usuario (No autenticado)
            return response()->json(['message' => 'No autenticado. Se requiere un token Bearer.'], 401);
        }

        // 3. Verificar si el rol del usuario está en la lista de roles permitidos
        // Asegúrate de que $user->rol exista y contenga el valor del rol (ej: 'admin', 'editor')
        if (!in_array($user->rol, $roles)) {
            // Error 403 si el usuario está autenticado pero no tiene el rol (No autorizado)
            return response()->json(['message' => 'Acceso denegado. No tiene el rol necesario.'], 403);
        }

        return $next($request);
    }
}
