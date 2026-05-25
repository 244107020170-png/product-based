<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPlayer
{
    /**
     * Middleware untuk memastikan user memiliki role player
     * 
     * Catatan: User dengan role 'player' adalah role default untuk user biasa.
     * Middleware ini gunakan jika ada route yang hanya untuk player saja.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user tidak authenticated, redirect ke login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Jika user tidak memiliki role player, abort dengan 403 (forbidden)
        if ($request->user()->role !== 'player') {
            abort(403, 'Unauthorized: Player access required');
        }

        return $next($request);
    }
}
