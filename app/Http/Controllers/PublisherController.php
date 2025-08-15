<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Models\Journal;
use App\Models\LoaRequest;
use App\Models\LoaValidated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function __construct()
    {
        // Removed middleware to fix access issue temporarily
    }

    /**
     * Publisher dashboard
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = Auth::user();
        
        // Simple role check
        if ($user->role !== 'publisher') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Halaman ini khusus untuk publisher.');
        }
        
        // Get publisher's journals (with null check)
        $journalIds = collect();
        if (method_exists($user, 'journals')) {
            $journalIds = $user->journals()->pluck('id');
        }
        
        // Statistics with safe queries
        $stats = [
            'publishers' => method_exists($user, 'publishers') ? $user->publishers()->count() : 0,
            'journals' => method_exists($user, 'journals') ? $user->journals()->count() : 0,
            'loa_requests' => [
                'total' => LoaRequest::whereIn('journal_id', $journalIds)->count(),
                'pending' => LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'pending')->count(),
                'approved' => LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'approved')->count(),
                'rejected' => LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'rejected')->count(),
            ],
            'validated' => LoaValidated::whereIn('journal_id', $journalIds)->count()
        ];

        // Recent LOA requests
        $recentRequests = LoaRequest::whereIn('journal_id', $journalIds)
            ->with(['journal.publisher', 'journal.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('publisher.dashboard', compact('stats', 'recentRequests'));
    }

    /**
     * Show publishers list
     */
    public function publishers()
    {
        $publishers = Auth::user()->publishers()->paginate(10);
        return view('publisher.publishers.index', compact('publishers'));
    }

    /**
     * Show create publisher form
     */
    public function createPublisher()
    {
        return view('publisher.publishers.create');
    }

    /**
     * Store new publisher
     */
    public function storePublisher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'address', 'phone', 'email', 'website']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        Publisher::create($data);

        return redirect()->route('publisher.publishers')
            ->with('success', 'Publisher berhasil dibuat!');
    }

    /**
     * Show journals list
     */
    public function journals()
    {
        $journals = Auth::user()->journals()->with('publisher')->paginate(10);
        return view('publisher.journals.index', compact('journals'));
    }

    /**
     * Show create journal form
     */
    public function createJournal()
    {
        $publishers = Auth::user()->publishers()->get();
        return view('publisher.journals.create', compact('publishers'));
    }

    /**
     * Store new journal
     */
    public function storeJournal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publisher_id' => 'required|exists:publishers,id',
            'name' => 'required|string|max:255',
            'e_issn' => 'nullable|string|max:20',
            'p_issn' => 'nullable|string|max:20',
            'chief_editor' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ttd_stample' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify publisher belongs to current user
        $publisher = Publisher::where('id', $request->publisher_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $data = $request->only(['publisher_id', 'name', 'e_issn', 'p_issn', 'chief_editor', 'website']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('journals', 'public');
        }

        if ($request->hasFile('ttd_stample')) {
            $data['ttd_stample'] = $request->file('ttd_stample')->store('signatures', 'public');
        }

        Journal::create($data);

        return redirect()->route('publisher.journals')
            ->with('success', 'Jurnal berhasil dibuat!');
    }

    /**
     * Show LOA requests
     */
    public function loaRequests()
    {
        // Get journals owned by the current publisher user
        $journalIds = Journal::where('user_id', Auth::id())->pluck('id');
        
        $requests = LoaRequest::whereIn('journal_id', $journalIds)
            ->with(['journal.publisher', 'loaValidated'])
            ->latest()
            ->paginate(15);

        return view('publisher.loa-requests.index', compact('requests'));
    }

    /**
     * Show LOA request detail
     */
    public function showLoaRequest(LoaRequest $loaRequest)
    {
        try {
            \Log::info('showLoaRequest called', ['loa_request_id' => $loaRequest->id]);
            
            // Load relationships
            $loaRequest->load(['journal.publisher', 'loaValidated']);
            
            \Log::info('Relationships loaded', [
                'journal' => $loaRequest->journal ? $loaRequest->journal->name : 'No journal',
                'publisher' => $loaRequest->journal && $loaRequest->journal->publisher ? $loaRequest->journal->publisher->name : 'No publisher'
            ]);

            \Log::info('Returning view', ['view' => 'publisher.loa-requests.show']);

            return view('publisher.loa-requests.show', compact('loaRequest'));
            
        } catch (\Exception $e) {
            \Log::error('Error in showLoaRequest', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('publisher.loa-requests.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail LOA request.');
        }
    }

    /**
     * Approve LOA request
     */
    public function approveLoaRequest(LoaRequest $loaRequest)
    {
        // Verify this request belongs to publisher's journal
        $journalIds = Journal::where('user_id', Auth::id())->pluck('id');
        if (!$journalIds->contains($loaRequest->journal_id)) {
            abort(403, 'Unauthorized access to this LOA request');
        }

        $loaRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        // Create validated LOA entry
        LoaValidated::create([
            'registration_number' => $loaRequest->registration_number,
            'user_id' => $loaRequest->user_id,
            'journal_id' => $loaRequest->journal_id,
            'article_title' => $loaRequest->article_title,
            'authors' => $loaRequest->authors,
            'corresponding_author' => $loaRequest->corresponding_author,
            'corresponding_email' => $loaRequest->corresponding_email,
            'article_type' => $loaRequest->article_type,
            'submission_date' => $loaRequest->submission_date,
            'approved_date' => now(),
            'approved_by' => Auth::id()
        ]);

        return redirect()->route('publisher.loa-requests')
            ->with('success', 'LOA request berhasil disetujui!');
    }

    /**
     * Reject LOA request
     */
    public function rejectLoaRequest(Request $request, LoaRequest $loaRequest)
    {
        // Verify this request belongs to publisher's journal
        if (!Auth::user()->journals()->where('id', $loaRequest->journal_id)->exists()) {
            abort(403, 'Unauthorized access to this LOA request');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $loaRequest->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('publisher.loa-requests')
            ->with('success', 'LOA request berhasil ditolak!');
    }

    /**
     * Show publisher profile
     */
    public function profile()
    {
        return view('publisher.profile');
    }

    /**
     * Update publisher profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $data = $request->only(['name', 'email']);

        // Check current password if new password provided
        if ($request->filled('password')) {
            if (!password_verify($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Password saat ini tidak benar'])
                    ->withInput();
            }
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('publisher.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
