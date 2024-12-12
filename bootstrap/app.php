<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        // $middleware->append(AdminMiddleware::class);
        // $middleware->append(UserMiddleware::class);
    
        $middleware->alias([
            'AdminAuth' => \App\Http\Middleware\AdminAuth::class,
            'SuperAdminAuth' => \App\Http\Middleware\SuperAdminAuth::class,
        ]);
        // $middleware->append(UpgrateToHttpsUnderNgrok::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
