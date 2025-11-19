<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Maneja una solicitud no autenticada.
     */
    protected function redirectTo($request): ?string
    {
        // Para APIs, devolvemos JSON en lugar de redirigir
        if (!$request->expectsJson()) {
            abort(response()->json([
                'message' => 'No autenticado. Token inválido o ausente.'
            ], 401));
        }

        return null;
    }
}
