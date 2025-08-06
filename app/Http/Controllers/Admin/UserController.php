<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrator,publisher,member'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_admin' => $request->role === 'administrator',
            'email_verified_at' => now()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:administrator,publisher,member',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_admin' => $request->role === 'administrator'
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        \Log::info('UserController@destroy called', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'current_user_id' => auth()->id()
        ]);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            \Log::warning('Attempted self-deletion', [
                'user_id' => $user->id,
                'auth_user_id' => auth()->id()
            ]);
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        try {
            $userName = $user->name;
            $user->delete();
            
            \Log::info('User deleted successfully', [
                'deleted_user' => $userName,
                'deleted_by' => auth()->user()->name
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', "User '{$userName}' berhasil dihapus.");
                
        } catch (\Exception $e) {
            \Log::error('Error deleting user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage());
        }
    }

    public function toggleRole(User $user)
    {
        \Log::info('UserController@toggleRole called', [
            'user_id' => $user->id,
            'current_role' => $user->role
        ]);

        // Prevent self role change
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Anda tidak bisa mengubah role sendiri.'
            ], 400);
        }

        try {
            $oldRole = $user->role;
            
            // Cycle through roles: member -> publisher -> admin -> member
            switch ($user->role) {
                case 'member':
                    $user->role = 'publisher';
                    break;
                case 'publisher':
                    $user->role = 'admin';
                    break;
                case 'admin':
                    $user->role = 'member';
                    break;
                default:
                    $user->role = 'member';
                    break;
            }
            
            $user->save();
            
            \Log::info('User role toggled successfully', [
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $user->role
            ]);

            return response()->json([
                'success' => true,
                'message' => "Role user berhasil diubah menjadi " . $user->getRoleDisplayName() . ".",
                'new_role' => $user->role,
                'new_role_display' => $user->getRoleDisplayName()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error toggling user role', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengubah role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail(User $user)
    {
        \Log::info('UserController@verifyEmail called', [
            'user_id' => $user->id,
            'current_verified_at' => $user->email_verified_at
        ]);

        try {
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'error' => 'Email user sudah terverifikasi.'
                ], 400);
            }

            $user->email_verified_at = now();
            $user->save();
            
            \Log::info('User email verified successfully', [
                'user_id' => $user->id,
                'verified_at' => $user->email_verified_at,
                'verified_by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Email {$user->email} berhasil diverifikasi.",
                'verified_at' => $user->email_verified_at->format('d/m/Y H:i')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error verifying user email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memverifikasi email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resendVerification(User $user)
    {
        \Log::info('UserController@resendVerification called', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        try {
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'error' => 'Email user sudah terverifikasi.'
                ], 400);
            }

            // In a real application, you would send an email here
            // For now, we'll just log and return success
            
            \Log::info('Verification email would be sent', [
                'user_id' => $user->id,
                'email' => $user->email,
                'sent_by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Email verifikasi berhasil dikirim ke {$user->email}."
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error sending verification email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengirim email verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unverifyEmail(User $user)
    {
        \Log::info('UserController@unverifyEmail called', [
            'user_id' => $user->id,
            'current_verified_at' => $user->email_verified_at
        ]);

        try {
            if (!$user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'error' => 'Email user belum terverifikasi.'
                ], 400);
            }

            $user->email_verified_at = null;
            $user->save();
            
            \Log::info('User email unverified successfully', [
                'user_id' => $user->id,
                'unverified_by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Email {$user->email} berhasil di-set sebagai belum terverifikasi."
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error unverifying user email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat menghapus verifikasi email: ' . $e->getMessage()
            ], 500);
        }
    }
}
