<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Debug: Log the attempt
        \Log::info('Login attempt', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Debug: Log user info
            \Log::info('User authenticated', ['user_id' => $user->id, 'is_admin' => $user->is_admin]);
            
            // Check if user is admin
            if ($user->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, Admin!');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses ditolak. Anda bukan admin.',
                ]);
            }
        }

        // Debug: Log failed attempt
        \Log::info('Login failed', ['email' => $credentials['email']]);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'Anda telah logout.');
    }

    /**
     * Create default admin user (for development)
     */
    public function createAdmin()
    {
        try {
            // First, check if users table has is_admin column
            if (!\Schema::hasColumn('users', 'is_admin')) {
                // Add the column manually
                \Schema::table('users', function ($table) {
                    $table->boolean('is_admin')->default(false)->after('email');
                });
            }

            $admin = User::firstOrCreate(
                ['email' => 'admin@loasiptenan.com'],
                [
                    'name' => 'Administrator',
                    'password' => Hash::make('admin123'),
                    'is_admin' => true,
                    'email_verified_at' => now()
                ]
            );

            // Create test admin too
            $testAdmin = User::firstOrCreate(
                ['email' => 'admin@test.com'],
                [
                    'name' => 'Test Admin',
                    'password' => Hash::make('password'),
                    'is_admin' => true,
                    'email_verified_at' => now()
                ]
            );

            // Show all users for debugging
            $allUsers = User::all();

            return response()->json([
                'message' => 'Admin users created/verified successfully',
                'admin_user' => [
                    'email' => 'admin@loasiptenan.com',
                    'password' => 'admin123'
                ],
                'test_admin' => [
                    'email' => 'admin@test.com', 
                    'password' => 'password'
                ],
                'all_users' => $allUsers->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin ?? false
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create admin user',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
