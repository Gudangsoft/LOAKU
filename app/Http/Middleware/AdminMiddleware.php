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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin (using web guard only)
        if (!Auth::guard('web')->check() || !Auth::guard('web')->user()->is_admin) {
            // Redirect to login with message
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Silakan login sebagai admin.');
        }

        return $next($request);
    }
}
