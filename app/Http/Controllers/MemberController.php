<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MemberController extends Controller
{
    /**
     * Show member registration form
     */
    public function showRegistrationForm()
    {
        return view('member.register');
    }

    /**
     * Handle member registration
     */
    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'organization' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the member user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'member',
            'status' => 'active',
            'full_name' => $request->name,
            'permissions' => [
                'manage_publishers',
                'manage_journals',
                'approve_loa_requests',
                'view_loa_requests',
                'manage_loa_requests'
            ]
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect with success message
        return redirect()->route('member.dashboard')->with('success', 'Pendaftaran member berhasil! Selamat datang di LOA SIPTENAN.');
    }

    /**
     * Show member login form
     */
    public function showLoginForm()
    {
        return view('member.login');
    }

    /**
     * Handle member login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is a member
            if (!$user->isMember() && !$user->isAdministrator()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses khusus untuk member LOA SIPTENAN.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('member.dashboard'))->with('success', 'Login member berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    /**
     * Show member dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Check if user has member role or is admin
        if (!$user->hasRole('member') && !$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan member.');
        }

        // Get member's LOA request statistics (for journals they own)
        $journalIds = $user->journals->pluck('id');
        
        $my_requests = [
            'total' => \App\Models\LoaRequest::whereIn('journal_id', $journalIds)->count(),
            'pending' => \App\Models\LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'pending')->count(),
            'approved' => \App\Models\LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'approved')->count(),
            'rejected' => \App\Models\LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'rejected')->count(),
        ];

        // Get recent requests for journals owned by this member
        $recent_requests = \App\Models\LoaRequest::whereIn('journal_id', $journalIds)
            ->with(['journal', 'loaValidated'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('member.dashboard', compact('my_requests', 'recent_requests'));
    }

    /**
     * Show member requests page
     */
    public function requests()
    {
        $user = Auth::user();
        
        // Check if user has member role or is admin
        if (!$user->hasRole('member') && !$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan member.');
        }

        // Get all requests for journals owned by this member
        $journalIds = $user->journals->pluck('id');
        
        $requests = \App\Models\LoaRequest::whereIn('journal_id', $journalIds)
            ->with(['journal', 'journal.publisher', 'loaValidated'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.requests', compact('requests'));
    }

    /**
     * Get request details via AJAX
     */
    public function getRequest($id)
    {
        $user = Auth::user();
        $journalIds = $user->journals->pluck('id');
        
        $request = \App\Models\LoaRequest::where('id', $id)
            ->whereIn('journal_id', $journalIds)
            ->with(['journal', 'journal.publisher'])
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Request tidak ditemukan'], 404);
        }

        return response()->json($request);
    }

    /**
     * Show member profile page
     */
    public function profile()
    {
        return view('member.profile');
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'organization' => $request->organization,
        ]);

        return redirect()->route('member.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update member password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('member.profile')->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Handle member logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout member berhasil!');
    }
}
