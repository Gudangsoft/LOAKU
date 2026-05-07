<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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

        $loaRequest->status = 'approved';
        $loaRequest->approved_at = now();
        $loaRequest->save();

        // Generate LOA code based on article ID if available
        $loaCode = LoaValidated::generateLoaCodeWithArticleId($loaRequest->article_id);
        
        $loaValidated = LoaValidated::create([
            'loa_request_id' => $loaRequest->id,
            'loa_code' => $loaCode,
            'verification_url' => route('loa.verify')
        ]);

        $loaRequest->loa_code = $loaCode;

        ActivityLog::record('approve_loa', "Menyetujui LOA \"{$loaRequest->article_title}\" (kode: {$loaCode})", $loaRequest, [
            'author'      => $loaRequest->author,
            'author_email'=> $loaRequest->author_email,
            'loa_code'    => $loaCode,
        ]);

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

        $loaRequest->status = 'rejected';
        $loaRequest->admin_notes = $request->admin_notes;
        $loaRequest->save();

        ActivityLog::record('reject_loa', "Menolak LOA \"{$loaRequest->article_title}\"", $loaRequest, [
            'author'      => $loaRequest->author,
            'author_email'=> $loaRequest->author_email,
            'reason'      => $request->admin_notes,
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

    public function export(Request $request)
    {
        $query = LoaRequest::with(['journal.publisher', 'loaValidated'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get();

        $filename = 'loa-requests-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
            fputcsv($out, ['No. Reg', 'Kode LOA', 'Judul Artikel', 'Penulis', 'Email Penulis',
                           'Jurnal', 'Publisher', 'Vol', 'No', 'Bulan', 'Tahun',
                           'Status', 'Tanggal Pengajuan', 'Tanggal Disetujui', 'Catatan Admin']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->no_reg,
                    $r->loaValidated?->loa_code ?? '-',
                    $r->article_title,
                    $r->author,
                    $r->author_email,
                    $r->journal?->name ?? '-',
                    $r->journal?->publisher?->name ?? '-',
                    $r->volume,
                    $r->number,
                    $r->month,
                    $r->year,
                    $r->status,
                    $r->created_at->format('d/m/Y H:i'),
                    $r->approved_at ? $r->approved_at->format('d/m/Y H:i') : '-',
                    $r->admin_notes ?? '-',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
