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
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('web')->user();
        
        // Check if user has admin privileges
        if (!$this->isAdminUser($user)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Access denied'], 403);
            }
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki hak akses admin.');
        }

        return $next($request);
    }

    /**
     * Check if user is admin
     */
    private function isAdminUser($user): bool
    {
        // Check direct admin flag
        if ($user->is_admin) {
            return true;
        }

        // Check if user has admin roles
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('super_admin') || 
                   $user->hasRole('administrator') || 
                   $user->hasRole('admin');
        }

        // Fallback to role field
        return in_array($user->role, ['super_admin', 'administrator', 'admin']);
    }
}
