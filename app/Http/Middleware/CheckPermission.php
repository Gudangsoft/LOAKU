<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // Skip permission check for super admin
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Check if user has any of the required permissions
        if (!empty($permissions) && !$user->hasAnyPermission($permissions)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini.',
                    'required_permissions' => $permissions
                ], 403);
            }

            abort(403, 'Anda tidak memiliki akses untuk melakukan tindakan ini. Diperlukan salah satu permission: ' . implode(', ', $permissions));
        }

        return $next($request);
    }
}
