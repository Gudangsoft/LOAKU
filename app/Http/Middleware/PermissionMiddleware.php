<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Super admin has access to everything
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $next($request);
        }

        // If specific permission is required
        if ($permission) {
            if (!$this->hasPermission($user, $permission)) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Access denied'], 403);
                }
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan tindakan ini.');
            }
        }

        return $next($request);
    }

    /**
     * Check if user has permission
     */
    private function hasPermission($user, string $permission): bool
    {
        // Map permissions to roles/conditions
        $permissionMap = [
            'manage_users' => ['super_admin', 'administrator'],
            'create_publisher' => ['super_admin', 'administrator', 'editor'],
            'create_journal' => ['super_admin', 'administrator', 'editor'],
            'validate_loa' => ['super_admin', 'administrator'],
            'view_statistics' => ['super_admin', 'administrator'],
            'admin_dashboard' => ['super_admin', 'administrator'],
            'system_configuration' => ['super_admin']
        ];

        if (!isset($permissionMap[$permission])) {
            return false;
        }

        $allowedRoles = $permissionMap[$permission];

        // Check if user has any of the allowed roles
        foreach ($allowedRoles as $role) {
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return true;
            }
            if ($user->role === $role) {
                return true;
            }
            if ($role === 'administrator' && $user->is_admin) {
                return true;
            }
        }

        return false;
    }
}
