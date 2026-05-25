<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases untuk role-based protection
        $middleware->alias([
            'role.admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'role.owner' => \App\Http\Middleware\EnsureUserIsOwner::class,
            'role.player' => \App\Http\Middleware\EnsureUserIsPlayer::class,
            'ownership' => \App\Http\Middleware\EnsureOwnership::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if ($exception->getStatusCode() !== 403 || ! $request->is('admin', 'admin/*')) {
                return null;
            }

            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            return redirect('/admin/login');
        });
    })->create();
