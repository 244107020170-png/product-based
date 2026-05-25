<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Middleware untuk memastikan user memiliki role admin
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user tidak authenticated, redirect ke login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Jika user tidak memiliki role admin, abort dengan 403 (forbidden)
        if ($request->user()->role !== 'admin') {
            abort(403, 'Unauthorized: Admin access required');
        }

        return $next($request);
    }
}
