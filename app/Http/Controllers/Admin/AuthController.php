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
            \Log::info('User authenticated', [
                'user_id' => $user->id, 
                'is_admin' => $user->is_admin,
                'role' => $user->role
            ]);
            
            // Check if user is admin or has admin privileges
            if ($user->is_admin || $user->hasRole('super_admin') || $user->hasRole('administrator')) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, Admin!');
            } elseif ($user->role === 'publisher') {
                // For publisher users, redirect to publisher dashboard
                $request->session()->regenerate();
                return redirect()->route('publisher.dashboard')
                    ->with('success', 'Selamat datang, Publisher ' . $user->name . '!');
            } else {
                // For regular members, redirect to member dashboard
                $request->session()->regenerate();
                return redirect()->route('member.dashboard')
                    ->with('success', 'Selamat datang, ' . $user->name . '!');
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
     * Show admin register form (requires existing admin session)
     */
    public function showRegisterForm()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        return view('admin.auth.register');
    }

    /**
     * Handle admin registration (requires existing admin session)
     */
    public function register(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:member,administrator'
        ]);

        try {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->is_admin = true;
            $user->role = $request->role;
            $user->email_verified_at = now();
            $user->save();

            try {
                $user->assignRole($request->role);
            } catch (\Exception $e) {
                // Role system optional
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Akun admin berhasil dibuat untuk ' . $user->name . '.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage()
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Show create admin form
     */
    public function showCreateAdminForm()
    {
        return view('admin.create-admin');
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

            if (!\Schema::hasColumn('users', 'role')) {
                \Schema::table('users', function ($table) {
                    $table->string('role')->default('admin')->after('is_admin');
                });
            }

            $admin = User::firstOrCreate(
                ['email' => 'admin@loasiptenan.com'],
                [
                    'name' => 'Administrator',
                    'password' => Hash::make('admin123'),
                    'is_admin' => true,
                    'role' => 'super_admin',
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
                    'role' => 'admin',
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
                        'is_admin' => $user->is_admin ?? false,
                        'role' => $user->role ?? 'user'
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
