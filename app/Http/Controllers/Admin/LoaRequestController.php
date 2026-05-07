<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaRequest;
use App\Models\LoaValidated;
use App\Notifications\LoaApprovedNotification;
use App\Notifications\LoaRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class LoaRequestController extends Controller
{
    public function index()
    {
        $requests = LoaRequest::with(['journal.publisher', 'loaValidated'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.loa-requests.index', compact('requests'));
    }

    public function show(LoaRequest $loaRequest)
    {
        $loaRequest->load(['journal.publisher']);
        return view('admin.loa-requests.show', compact('loaRequest'));
    }

    public function approve(LoaRequest $loaRequest)
    {
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
        
        $loaValidated = LoaValidated::create([
            'loa_request_id' => $loaRequest->id,
            'loa_code' => $loaCode,
            'verification_url' => route('loa.verify')
        ]);

        // Reload to get loa_code on loaRequest
        $loaRequest->loa_code = $loaCode;

        try {
            Notification::route('mail', $loaRequest->author_email)
                ->notify(new LoaApprovedNotification($loaRequest));
        } catch (\Exception $e) {
            \Log::error('Failed to send LOA approval email: ' . $e->getMessage());
        }

        return redirect()->route('admin.loa-requests.index')
            ->with('success', 'LOA request berhasil disetujui dengan kode: ' . $loaCode);
    }

    public function reject(Request $request, LoaRequest $loaRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi.'
        ]);

        if ($loaRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'LOA request sudah diproses sebelumnya.');
        }

        $loaRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        try {
            Notification::route('mail', $loaRequest->author_email)
                ->notify(new LoaRejectedNotification($loaRequest));
        } catch (\Exception $e) {
            \Log::error('Failed to send LOA rejection email: ' . $e->getMessage());
        }

        return redirect()->route('admin.loa-requests.index')
            ->with('success', 'LOA request berhasil ditolak.');
    }
}
