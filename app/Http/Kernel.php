<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
      protected $middleware = [
        // Asegúrate de que esté aquí, al principio si es posible
        \Illuminate\Http\Middleware\HandleCors::class, // <-- AÑADE ESTA LÍNEA
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\CheckForAnyProxy::class, // O Illuminate\Http\Middleware\TrustProxies si usas proxies
    ];

    protected $middlewareGroups = [
        'api' => [
            // throttle, bindings, etc. si los necesitas
            // Puedes mover HandleCors aquí si solo quieres CORS para rutas /api
        ],
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // ... otros middlewares web
        ],
    ];

    protected $routeMiddleware = [
        'rol' => \App\Http\Middleware\RolMiddleware::class,
    ];
}
