<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaRequest;
use App\Models\LoaValidated;
use Illuminate\Http\Request;

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
        
        LoaValidated::create([
            'loa_request_id' => $loaRequest->id,
            'loa_code' => $loaCode,
            'verification_url' => route('loa.verify')
        ]);

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

        return redirect()->route('admin.loa-requests.index')
            ->with('success', 'LOA request berhasil ditolak.');
    }
}
