<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PublisherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        
        // Check if user has publisher role
        if ($user->role !== 'publisher') {
            abort(403, 'Anda tidak memiliki akses ke halaman publisher. Akses ini khusus untuk pengelola jurnal.');
        }

        return $next($request);
    }
}
