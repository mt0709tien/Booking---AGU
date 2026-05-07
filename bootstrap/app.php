<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Cấu hình Alias cho Middleware của bạn
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // CÁCH GIẢI QUYẾT LỖI 419:
        // Tạm thời bỏ qua kiểm tra CSRF cho tất cả các route để kiểm tra
        $middleware->validateCsrfTokens(except: [
            '*', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();