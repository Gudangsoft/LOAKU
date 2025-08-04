<?php

namespace App\Http\Controllers;

use App\Models\LoaRequest;
use App\Models\LoaValidated;
use App\Models\LoaTemplate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LoaController extends Controller
{
    /**
     * Generate QR Code with fallback for ImageMagick issues
     */
    private function generateQrCode($url)
    {
        // Temporary disable QR code generation until ImageMagick is installed
        // This will prevent PDF generation from failing
        return null;
        
        /* 
        // Uncomment this section once ImageMagick extension is installed
        try {
            // Try with SVG format first (doesn't require ImageMagick)
            return base64_encode(QrCode::format('svg')->size(200)->generate($url));
        } catch (\Exception $e) {
            try {
                // Fallback to PNG with different backend
                return base64_encode(QrCode::format('png')->size(200)->generate($url));
            } catch (\Exception $e2) {
                // Log the error for debugging
                \Log::warning('QR Code generation failed: ' . $e2->getMessage());
                
                // Return null - template will handle missing QR code gracefully
                return null;
            }
        }
        */
    }

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

    public function verifyResult($loaCode)
    {
        $loaValidated = LoaValidated::where('loa_code', $loaCode)
            ->with(['loaRequest.journal.publisher'])
            ->first();

        if (!$loaValidated) {
            return view('loa.verification-result', [
                'error' => 'LOA tidak ditemukan atau belum divalidasi.',
                'loaValidated' => null
            ]);
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
                // Generate QR Code for verification - with fallback for ImageMagick issues
                $verificationUrl = route('loa.verify.result', $loaValidated->loa_code);
                $qrCode = $this->generateQrCode($verificationUrl);
                
                $data = [
                    'loa' => $loaValidated,
                    'request' => $loaValidated->loaRequest,
                    'journal' => $loaValidated->loaRequest ? $loaValidated->loaRequest->journal : null,
                    'publisher' => $loaValidated->loaRequest && $loaValidated->loaRequest->journal ? $loaValidated->loaRequest->journal->publisher : null,
                    'lang' => $lang,
                    'qrCode' => $qrCode
                ];
            }

            // Check if new PDF template exists, fallback to old template
            $templateName = view()->exists('pdf.loa-new-format') ? 'pdf.loa-new-format' : 'pdf.loa-certificate';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($templateName, $data);
            $pdf->setPaper('A4', 'portrait');

            $filename = $loaCode . '_' . $lang . '.pdf';
            
            return $pdf->stream($filename);
            
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

        // Get publisher ID for template selection
        $publisherId = $loaValidated->loaRequest->journal->publisher_id ?? null;
        
        // Try to get custom template, fallback to default
        $template = LoaTemplate::getDefault($lang, $publisherId);
        
        $data = [
            'loa' => $loaValidated,
            'request' => $loaValidated->loaRequest,
            'journal' => $loaValidated->loaRequest->journal,
            'publisher' => $loaValidated->loaRequest->journal->publisher,
            'lang' => $lang,
            'template' => $template
        ];

        // If custom template exists, use it with template rendering
        if ($template) {
            return $this->renderDynamicTemplate($template, $data);
        }

        // Fallback to default view
        return view('loa.view', $data);
    }
    
    private function renderDynamicTemplate($template, $data)
    {
        // Prepare template variables
        $variables = [
            'publisher_name' => $data['publisher']->name ?? '',
            'publisher_address' => $data['publisher']->address ?? '',
            'publisher_email' => $data['publisher']->email ?? '',
            'publisher_phone' => $data['publisher']->phone ?? '',
            'publisher_logo' => $data['publisher']->logo ? asset('storage/' . $data['publisher']->logo) : '',
            'journal_name' => $data['journal']->name ?? '',
            'journal_issn_e' => $data['journal']->e_issn ?? '',
            'journal_issn_p' => $data['journal']->p_issn ?? '',
            'chief_editor' => $data['journal']->chief_editor ?? '',
            'signature_stamp' => $data['journal']->ttd_stample ? asset('storage/' . $data['journal']->ttd_stample) : '',
            'loa_code' => $data['loa']->loa_code ?? '',
            'article_title' => $data['request']->article_title ?? '',
            'author_name' => $data['request']->author ?? '',
            'author_email' => $data['request']->author_email ?? '',
            'volume' => $data['request']->volume ?? '',
            'number' => $data['request']->number ?? '',
            'month' => $data['request']->month ?? '',
            'year' => $data['request']->year ?? '',
            'registration_number' => $data['request']->no_reg ?? '',
            'approval_date' => isset($data['request']->approved_at) ? $data['request']->approved_at->format('d F Y') : $data['loa']->created_at->format('d F Y'),
            'current_date' => now()->format('d F Y, H:i:s'),
            'verification_url' => route('loa.verify'),
            'qr_code_url' => route('qr.code', $data['loa']->loa_code),
        ];

        // Render template content
        $content = $template->header_template . $template->body_template . $template->footer_template;
        
        // Replace variables
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        // Handle conditional statements for language
        $content = $this->processConditionals($content, $data['lang']);
        
        return response($content)->header('Content-Type', 'text/html');
    }
    
    private function processConditionals($content, $lang)
    {
        // Process @if($lang == "id") ... @else ... @endif
        $content = preg_replace_callback(
            '/@if\(\$lang\s*==\s*["\']id["\']\)(.*?)(?:@else(.*?))?@endif/s',
            function ($matches) use ($lang) {
                if ($lang == 'id') {
                    return $matches[1];
                } else {
                    return isset($matches[2]) ? $matches[2] : '';
                }
            },
            $content
        );
        
        // Process @if($lang == "en") ... @else ... @endif  
        $content = preg_replace_callback(
            '/@if\(\$lang\s*==\s*["\']en["\']\)(.*?)(?:@else(.*?))?@endif/s',
            function ($matches) use ($lang) {
                if ($lang == 'en') {
                    return $matches[1];
                } else {
                    return isset($matches[2]) ? $matches[2] : '';
                }
            },
            $content
        );
        
        return $content;
    }
}
