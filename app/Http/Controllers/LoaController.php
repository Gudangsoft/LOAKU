<?php

namespace App\Http\Controllers;

use App\Models\LoaRequest;
use App\Models\LoaValidated;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LoaController extends Controller
{
    public function search()
    {
        return view('loa.search');
    }

    public function find(Request $request)
    {
        $request->validate([
            'search' => 'required|string'
        ], [
            'search.required' => 'Kode LOA atau email wajib diisi.'
        ]);

        $search = $request->search;
        
        // Search by LOA code first
        $loaValidated = LoaValidated::where('loa_code', $search)
            ->with(['loaRequest.journal.publisher'])
            ->first();

        // If not found by LOA code, search by email
        if (!$loaValidated) {
            $loaRequest = LoaRequest::where('author_email', $search)
                ->where('status', 'approved')
                ->with(['journal.publisher', 'loaValidated'])
                ->first();
            
            if ($loaRequest && $loaRequest->loaValidated) {
                $loaValidated = $loaRequest->loaValidated;
            }
        }

        if (!$loaValidated) {
            return redirect()->back()
                ->with('error', 'LOA tidak ditemukan atau belum divalidasi.');
        }

        return view('loa.result', compact('loaValidated'));
    }

    public function verify()
    {
        return view('loa.verify');
    }

    public function checkVerification(Request $request)
    {
        $request->validate([
            'loa_code' => 'required|string'
        ], [
            'loa_code.required' => 'Kode LOA wajib diisi.'
        ]);

        $loaValidated = LoaValidated::where('loa_code', $request->loa_code)
            ->with(['loaRequest.journal.publisher'])
            ->first();

        if (!$loaValidated) {
            return redirect()->back()
                ->with('error', 'LOA tidak ditemukan atau belum divalidasi.');
        }

        return view('loa.verification-result', compact('loaValidated'));
    }

    public function validatedLoas(Request $request)
    {
        $query = LoaValidated::with(['loaRequest.journal.publisher']);

        // Search by LOA code
        if ($request->filled('loa_code')) {
            $query->where('loa_code', 'like', '%' . $request->loa_code . '%');
        }

        // Search by article title
        if ($request->filled('title')) {
            $query->whereHas('loaRequest', function($q) use ($request) {
                $q->where('article_title', 'like', '%' . $request->title . '%');
            });
        }

        // Search by author
        if ($request->filled('author')) {
            $query->whereHas('loaRequest', function($q) use ($request) {
                $q->where('author', 'like', '%' . $request->author . '%');
            });
        }

        // Search by journal
        if ($request->filled('journal_id')) {
            $query->whereHas('loaRequest', function($q) use ($request) {
                $q->where('journal_id', $request->journal_id);
            });
        }

        // Search by year
        if ($request->filled('year')) {
            $query->whereHas('loaRequest', function($q) use ($request) {
                $q->where('year', $request->year);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'loa_code':
                $query->orderBy('loa_code', 'asc');
                break;
            case 'title':
                $query->whereHas('loaRequest', function($q) {
                    $q->orderBy('article_title', 'asc');
                });
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $validatedLoas = $query->paginate(12)->appends($request->query());

        // Get journals for filter dropdown
        $journals = \App\Models\Journal::orderBy('name')->get();

        return view('loa.validated-list', compact('validatedLoas', 'journals'));
    }

    public function print($loaCode, $lang = 'id')
    {
        try {
            $loaValidated = LoaValidated::where('loa_code', $loaCode)
                ->with(['loaRequest.journal.publisher'])
                ->first();

            if (!$loaValidated) {
                // If no data found, create demo data for testing
                $data = [
                    'loa' => (object)['loa_code' => $loaCode],
                    'request' => (object)[
                        'article_title' => 'Sample Article Title',
                        'author' => 'Sample Author',
                        'author_email' => 'author@example.com',
                        'volume' => '1',
                        'number' => '1',
                        'month' => date('F'),
                        'year' => date('Y'),
                        'no_reg' => 'REG001',
                        'approved_at' => (object)['format' => function($format) { return date($format); }]
                    ],
                    'journal' => (object)[
                        'name' => 'Sample Journal',
                        'e_issn' => '0000-0000',
                        'p_issn' => '0000-0000',
                        'chief_editor' => 'Sample Editor'
                    ],
                    'publisher' => (object)[
                        'name' => 'Sample Publisher',
                        'address' => 'Sample Address',
                        'email' => 'publisher@example.com',
                        'phone' => '+62-xxx-xxxx'
                    ],
                    'lang' => $lang
                ];
            } else {
                $data = [
                    'loa' => $loaValidated,
                    'request' => $loaValidated->loaRequest,
                    'journal' => $loaValidated->loaRequest ? $loaValidated->loaRequest->journal : null,
                    'publisher' => $loaValidated->loaRequest && $loaValidated->loaRequest->journal ? $loaValidated->loaRequest->journal->publisher : null,
                    'lang' => $lang
                ];
            }

            // Check if PDF template exists
            if (!view()->exists('pdf.loa-certificate')) {
                return response()->json(['error' => 'PDF template not found'], 500);
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.loa-certificate', $data);
            $pdf->setPaper('A4', 'portrait');

            $filename = $loaCode . '_' . $lang . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function view($loaCode, $lang = 'id')
    {
        $loaValidated = LoaValidated::where('loa_code', $loaCode)
            ->with(['loaRequest.journal.publisher'])
            ->firstOrFail();

        $data = [
            'loa' => $loaValidated,
            'request' => $loaValidated->loaRequest,
            'journal' => $loaValidated->loaRequest->journal,
            'publisher' => $loaValidated->loaRequest->journal->publisher,
            'lang' => $lang
        ];

        return view('pdf.loa-certificate', $data);
    }
}
