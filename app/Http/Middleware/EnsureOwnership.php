<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnership
{
    /**
     * Middleware untuk validasi ownership (pemilik lapangan/resource)
     * 
     * Gunakan middleware ini pada route yang memerlukan validasi owner_id
     * Contoh: Route::middleware(['auth', 'ensure.ownership:field'])->group(...)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $field - nama field dari model yang mengandung owner_id
     */
    public function handle(Request $request, Closure $next, $field = 'field'): Response
    {
        // Ambil model dari route parameter berdasarkan nama field
        // Contoh: jika field='field', ambil $request->route('field')
        $model = $request->route($field);

        if (!$model) {
            abort(404, 'Resource not found');
        }

        // Cek apakah user adalah owner dari resource ini
        // Asumsikan model memiliki column 'owner_id' atau relasi 'owner'
        $ownerId = $model->owner_id ?? $model->owner?->id ?? null;

        if (!$ownerId || $ownerId !== $request->user()->id) {
            abort(403, 'Unauthorized: You are not the owner of this resource');
        }

        return $next($request);
    }
}
