<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Debug route to check authentication status
Route::get('/debug-auth', function() {
    $user = Auth::user();
    
    if (!$user) {
        return response()->json([
            'status' => 'Not authenticated',
            'message' => 'User is not logged in'
        ]);
    }
    
    return response()->json([
        'status' => 'Authenticated',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'no role',
            'is_admin' => $user->is_admin ?? false
        ],
        'admin_check' => in_array($user->role, ['super_admin', 'administrator', 'admin']) || $user->is_admin
    ]);
});
