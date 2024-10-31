<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission = null)
    {
        // Pastikan pengguna sudah terautentikasi
        if (!Auth::check()) {
            abort(403, 'Access denied');
        }

        $user = Auth::user();

        // Jika permission disertakan dan user tidak memiliki permission tersebut, tolak akses
        if ($permission && !$user->can($permission)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
