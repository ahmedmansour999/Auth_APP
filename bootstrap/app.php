<?php

use App\Http\Middleware\notAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        function(Router $router){
            // $router->middleware('api')
            //     ->prefix('api')
            //     ->group(base_path('routes/api.php')) ;

            $router->middleware('web')
                ->group(base_path('routes/web.php')) ;

            $router->middleware('web')
                ->group(base_path('routes/auth.php')) ;

        } ,
        commands: __DIR__ . '/../routes/console.php',

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'notAuth' => notAuth::class
        ]);;
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
