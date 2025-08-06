<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        // Check if accounts table exists
        if (!Schema::hasTable('accounts')) {
            return redirect()->route('setup.role.system')
                           ->with('info', 'Silakan setup sistem role terlebih dahulu.');
        }
        
        $query = Account::with('publisher');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by publisher
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }

        $accounts = $query->latest()->paginate(15);
        $publishers = Publisher::all();

        return view('admin.accounts.index', compact('accounts', 'publishers'));
    }

    public function create()
    {
        $publishers = Publisher::all();
        $roles = Account::getAvailableRoles();
        $statuses = Account::getAvailableStatuses();

        return view('admin.accounts.create', compact('publishers', 'roles', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:accounts',
            'email' => 'required|string|email|max:255|unique:accounts',
            'password' => 'required|string|min:6|confirmed',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:administrator,publisher',
            'status' => 'required|in:active,inactive,suspended',
            'publisher_id' => 'nullable|exists:publishers,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['permissions'] = Account::getDefaultPermissions($request->role);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Validate publisher requirement for publisher role
        if ($request->role === 'publisher' && !$request->publisher_id) {
            return back()->withErrors(['publisher_id' => 'Publisher harus dipilih untuk role Publisher.']);
        }

        Account::create($data);

        return redirect()->route('admin.accounts.index')
                        ->with('success', 'Account berhasil dibuat.');
    }

    public function show(Account $account)
    {
        $account->load('publisher');
        return view('admin.accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $publishers = Publisher::all();
        $roles = Account::getAvailableRoles();
        $statuses = Account::getAvailableStatuses();

        return view('admin.accounts.edit', compact('account', 'publishers', 'roles', 'statuses'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('accounts')->ignore($account->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('accounts')->ignore($account->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:administrator,publisher',
            'status' => 'required|in:active,inactive,suspended',
            'publisher_id' => 'nullable|exists:publishers,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['password', 'password_confirmation', 'avatar']);

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($account->avatar) {
                Storage::disk('public')->delete($account->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Update permissions if role changed
        if ($request->role !== $account->role) {
            $data['permissions'] = Account::getDefaultPermissions($request->role);
        }

        // Validate publisher requirement for publisher role
        if ($request->role === 'publisher' && !$request->publisher_id) {
            return back()->withErrors(['publisher_id' => 'Publisher harus dipilih untuk role Publisher.']);
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')
                        ->with('success', 'Account berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        // Delete avatar if exists
        if ($account->avatar) {
            Storage::disk('public')->delete($account->avatar);
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')
                        ->with('success', 'Account berhasil dihapus.');
    }

    public function permissions(Account $account)
    {
        $allPermissions = [
            'manage_users' => 'Kelola Pengguna',
            'manage_accounts' => 'Kelola Akun',
            'manage_publishers' => 'Kelola Penerbit',
            'manage_journals' => 'Kelola Jurnal',
            'manage_loa_requests' => 'Kelola Permintaan LOA',
            'manage_templates' => 'Kelola Template',
            'view_analytics' => 'Lihat Analytics',
            'view_publisher_analytics' => 'Lihat Analytics Publisher',
            'system_settings' => 'Pengaturan Sistem',
        ];

        return view('admin.accounts.permissions', compact('account', 'allPermissions'));
    }

    public function updatePermissions(Request $request, Account $account)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $account->update([
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('admin.accounts.permissions', $account)
                        ->with('success', 'Permissions berhasil diperbarui.');
    }
}
