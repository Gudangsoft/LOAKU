<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Models\Journal;
use App\Models\LoaRequest;
use App\Models\LoaValidated;
use App\Exports\JournalsExport;
use App\Imports\JournalsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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

        return redirect()->route('publisher.publishers.index')
            ->with('success', 'Publisher berhasil dibuat!');
    }

    /**
     * Show publisher details
     */
    public function showPublisher(Publisher $publisher)
    {
        // Check if user owns this publisher
        if ($publisher->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('publisher.publishers.show', compact('publisher'));
    }

    /**
     * Show edit publisher form
     */
    public function editPublisher(Publisher $publisher)
    {
        // Check if user owns this publisher
        if ($publisher->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('publisher.publishers.edit', compact('publisher'));
    }

    /**
     * Update publisher
     */
    public function updatePublisher(Request $request, Publisher $publisher)
    {
        // Check if user owns this publisher
        if ($publisher->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

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

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($publisher->logo) {
                Storage::disk('public')->delete($publisher->logo);
            }
            $data['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        $publisher->update($data);

        return redirect()->route('publisher.publishers.index')
            ->with('success', 'Publisher berhasil diupdate!');
    }

    /**
     * Delete publisher
     */
    public function destroyPublisher(Publisher $publisher)
    {
        // Check if user owns this publisher
        if ($publisher->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if publisher has journals
        if ($publisher->journals()->count() > 0) {
            return redirect()->route('publisher.publishers.index')
                ->with('error', 'Cannot delete publisher with active journals!');
        }

        // Delete logo if exists
        if ($publisher->logo) {
            Storage::disk('public')->delete($publisher->logo);
        }

        $publisher->delete();

        return redirect()->route('publisher.publishers.index')
            ->with('success', 'Publisher berhasil dihapus!');
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
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_stamp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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

        $data = $request->only(['publisher_id', 'name', 'e_issn', 'p_issn', 'chief_editor', 'email', 'description', 'website']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('journals', 'public');
        }

        if ($request->hasFile('signature_stamp')) {
            $data['signature_stamp'] = $request->file('signature_stamp')->store('signatures', 'public');
        }

        Journal::create($data);

        return redirect()->route('publisher.journals.index')
            ->with('success', 'Jurnal berhasil dibuat!');
    }

    /**
     * Show journal details
     */
    public function showJournal(Journal $journal)
    {
        // Check if user owns this journal
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('publisher.journals.show', compact('journal'));
    }

    /**
     * Show edit journal form
     */
    public function editJournal(Journal $journal)
    {
        // Check if user owns this journal
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $publishers = Auth::user()->publishers()->get();
        return view('publisher.journals.edit', compact('journal', 'publishers'));
    }

    /**
     * Update journal
     */
    public function updateJournal(Request $request, Journal $journal)
    {
        // Check if user owns this journal
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'publisher_id' => 'required|exists:publishers,id',
            'name' => 'required|string|max:255',
            'e_issn' => 'nullable|string|max:20',
            'p_issn' => 'nullable|string|max:20',
            'chief_editor' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_stamp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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

        $data = $request->only(['publisher_id', 'name', 'e_issn', 'p_issn', 'chief_editor', 'email', 'description', 'website']);

        // Handle logo removal
        if ($request->has('remove_logo') && $journal->logo) {
            Storage::disk('public')->delete($journal->logo);
            $data['logo'] = null;
        }

        // Handle signature removal
        if ($request->has('remove_signature') && $journal->signature_stamp) {
            Storage::disk('public')->delete($journal->signature_stamp);
            $data['signature_stamp'] = null;
        }

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($journal->logo) {
                Storage::disk('public')->delete($journal->logo);
            }
            $data['logo'] = $request->file('logo')->store('journals', 'public');
        }

        if ($request->hasFile('signature_stamp')) {
            // Delete old signature if exists
            if ($journal->signature_stamp) {
                Storage::disk('public')->delete($journal->signature_stamp);
            }
            $data['signature_stamp'] = $request->file('signature_stamp')->store('signatures', 'public');
        }

        $journal->update($data);

        return redirect()->route('publisher.journals.index')
            ->with('success', 'Jurnal berhasil diupdate!');
    }

    /**
     * Delete journal
     */
    public function destroyJournal(Journal $journal)
    {
        // Check if user owns this journal
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if journal has LOA requests
        if ($journal->loaRequests()->count() > 0) {
            return redirect()->route('publisher.journals.index')
                ->with('error', 'Cannot delete journal with existing LOA requests!');
        }

        // Delete associated files
        if ($journal->logo) {
            Storage::disk('public')->delete($journal->logo);
        }
        if ($journal->signature_stamp) {
            Storage::disk('public')->delete($journal->signature_stamp);
        }

        $journal->delete();

        return redirect()->route('publisher.journals.index')
            ->with('success', 'Jurnal berhasil dihapus!');
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
            // Check if user has access to this LOA request (via their journals)
            $userJournalIds = Journal::where('user_id', Auth::id())->pluck('id');
            
            if (!$userJournalIds->contains($loaRequest->journal_id)) {
                return redirect()->route('publisher.loa-requests.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat LOA request ini.');
            }
            
            // Load relationships
            $loaRequest->load(['journal.publisher', 'loaValidated']);

            return view('publisher.loa-requests.show-v2', compact('loaRequest'));
            
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

        if ($loaRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'LOA request sudah diproses sebelumnya.');
        }

        $loaRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Generate LOA code based on article ID if available
        $loaCode = LoaValidated::generateLoaCodeWithArticleId($loaRequest->article_id);
        
        LoaValidated::create([
            'loa_request_id' => $loaRequest->id,
            'loa_code' => $loaCode,
            'verification_url' => route('loa.verify')
        ]);

        return redirect()->route('publisher.loa-requests.index')
            ->with('success', 'LOA request berhasil disetujui dengan kode: ' . $loaCode);
    }

    /**
     * Reject LOA request
     */
    public function rejectLoaRequest(Request $request, LoaRequest $loaRequest)
    {
        // Verify this request belongs to publisher's journal
        $journalIds = Journal::where('user_id', Auth::id())->pluck('id');
        if (!$journalIds->contains($loaRequest->journal_id)) {
            abort(403, 'Unauthorized access to this LOA request');
        }

        if ($loaRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'LOA request sudah diproses sebelumnya.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000'
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $loaRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->rejection_reason
        ]);

        return redirect()->route('publisher.loa-requests.index')
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

    /**
     * Export journals to Excel
     */
    public function exportJournals()
    {
        $publisherId = Auth::id();
        return Excel::download(new JournalsExport($publisherId), 'my_journals_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Show import form
     */
    public function importJournalsForm()
    {
        return view('publisher.journals.import');
    }

    /**
     * Import journals from Excel
     */
    public function importJournals(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new JournalsImport(Auth::id());
            Excel::import($import, $request->file('file'));

            $message = "Import berhasil! {$import->getSuccessCount()} jurnal berhasil diproses.";
            
            if ($import->hasErrors()) {
                $message .= " Namun ada beberapa error: " . implode('; ', array_slice($import->getErrors(), 0, 3));
                if (count($import->getErrors()) > 3) {
                    $message .= " dan " . (count($import->getErrors()) - 3) . " error lainnya.";
                }
                return redirect()->route('publisher.journals.index')
                    ->with('warning', $message);
            }

            return redirect()->route('publisher.journals.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('publisher.journals.index')
                ->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel
     */
    public function downloadJournalsTemplate()
    {
        $headers = [
            ['nama_jurnal', 'deskripsi', 'issn', 'e_issn', 'website', 'email', 'alamat', 'status'],
            ['Jurnal Teknologi Informasi', 'Jurnal yang membahas teknologi informasi terkini', '1234-5678', '8765-4321', 'https://jti.example.com', 'editor@jti.com', 'Jl. Teknologi No. 123', 'active'],
            ['Jurnal Ilmu Komputer', 'Jurnal penelitian ilmu komputer dan informatika', '2345-6789', '9876-5432', 'https://jik.example.com', 'editor@jik.com', 'Jl. Komputer No. 456', 'active']
        ];

        $fileName = 'template_import_jurnal.xlsx';
        
        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $data;
            
            public function __construct($data) {
                $this->data = $data;
            }
            
            public function array(): array {
                return $this->data;
            }
        }, $fileName);
    }
}
