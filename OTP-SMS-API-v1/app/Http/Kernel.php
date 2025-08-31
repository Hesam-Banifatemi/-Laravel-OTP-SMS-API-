<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware here
    ];

    protected $middlewareGroups = [
        'web' => [
            // web middleware group here
        ],

        'api' => [
            'throttle:api',
            'auth:sanctum', // ensure auth:sanctum is here
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        //'auth' => \App\Http\Middleware\Authenticate::class,
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // other route middleware
    ];
}
