<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Silakan masuk dengan akun Admin terlebih dahulu untuk mengakses Admin Panel.'
            ]);
        }

        // 2. Cek apakah role pengguna adalah admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses administrator ke halaman ini.');
        }

        return $next($request);
    }
}
