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
        // return null;


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
                    'loa'               => $loaValidated,
                    'request'           => $loaValidated->loaRequest,
                    'journal'           => $loaValidated->loaRequest ? $loaValidated->loaRequest->journal : null,
                    'publisher'         => $loaValidated->loaRequest && $loaValidated->loaRequest->journal ? $loaValidated->loaRequest->journal->publisher : null,
                    'lang'              => $lang,
                    'qrCode'            => $qrCode,
                    'digitalSignature'  => $loaValidated->digital_signature,
                    'signedAt'          => $loaValidated->signed_at,
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
        $variables = [
            'publisher_name'      => $data['publisher']->name ?? '',
            'publisher_address'   => $data['publisher']->address ?? '',
            'publisher_email'     => $data['publisher']->email ?? '',
            'publisher_phone'     => $data['publisher']->phone ?? '',
            'publisher_logo'      => $data['publisher']->logo ? asset('storage/' . $data['publisher']->logo) : '',
            'journal_name'        => $data['journal']->name ?? '',
            'journal_issn_e'      => $data['journal']->e_issn ?? '',
            'journal_issn_p'      => $data['journal']->p_issn ?? '',
            'chief_editor'        => $data['journal']->chief_editor ?? '',
            'signature_stamp'     => $data['journal']->ttd_stample ? asset('storage/' . $data['journal']->ttd_stample) : '',
            'loa_code'            => $data['loa']->loa_code ?? '',
            'article_title'       => $data['request']->article_title ?? '',
            'author_name'         => $data['request']->author ?? '',
            'author_email'        => $data['request']->author_email ?? '',
            'volume'              => $data['request']->volume ?? '',
            'number'              => $data['request']->number ?? '',
            'month'               => $data['request']->month ?? '',
            'year'                => $data['request']->year ?? '',
            'registration_number' => $data['request']->no_reg ?? '',
            'approval_date'       => isset($data['request']->approved_at) ? $data['request']->approved_at->format('d F Y') : $data['loa']->created_at->format('d F Y'),
            'current_date'        => now()->format('d F Y, H:i:s'),
            'verification_url'    => route('loa.verify'),
            'qr_code_url'         => route('qr.code', $data['loa']->loa_code),
        ];

        $content = $template->header_template . $template->body_template . $template->footer_template;

        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        $content = $this->processConditionals($content, $data['lang']);

        // Pre-compute values for the HTML wrapper
        $lang         = $data['lang'];
        $loaCode      = $data['loa']->loa_code;
        $pageTitle    = $lang === 'id' ? 'Surat Persetujuan Naskah' : 'Letter of Acceptance';
        $homeUrl      = route('home');
        $searchUrl    = route('loa.validated');
        $viewIdUrl    = route('loa.view', [$loaCode, 'id']);
        $viewEnUrl    = route('loa.view', [$loaCode, 'en']);
        $printIdUrl   = route('loa.print', [$loaCode, 'id']);
        $printEnUrl   = route('loa.print', [$loaCode, 'en']);
        $verifyUrl    = route('loa.verify');
        $homeLabel    = $lang === 'id' ? 'Beranda' : 'Home';
        $searchLabel  = $lang === 'id' ? 'Cari LOA' : 'Search LOA';
        $backLabel    = $lang === 'id' ? 'Kembali ke Pencarian' : 'Back to Search';
        $verifyLabel  = $lang === 'id' ? 'Verifikasi LOA Lain' : 'Verify Other LOA';
        $printLabel   = $lang === 'id' ? 'Cetak Halaman' : 'Print Page';
        $btnId        = $lang === 'id' ? 'btn-primary' : 'btn-outline-primary';
        $btnEn        = $lang === 'en' ? 'btn-primary' : 'btn-outline-primary';

        $html = <<<HTML
<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle} - {$loaCode}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', system-ui, sans-serif; }
        .loa-wrapper { max-width: 980px; margin: 0 auto; padding: 2rem 1rem 3rem; }
        .loa-certificate {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,.14);
            background: #fff;
            animation: fadeInUp .5s ease-out;
        }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
        .loa-inner { padding: 0; }
        /* Give inner content some breathing room if template doesn't add its own */
        .loa-inner > *:first-child { margin-top: 0 !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .loa-wrapper { padding: 0; max-width: 100%; }
            .loa-certificate { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>
<div class="loa-wrapper">

    <!-- Top bar: breadcrumb + download -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 no-print">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{$homeUrl}" class="text-decoration-none text-secondary">
                        <i class="fas fa-home me-1"></i>{$homeLabel}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{$searchUrl}" class="text-decoration-none text-secondary">{$searchLabel}</a>
                </li>
                <li class="breadcrumb-item active fw-semibold">{$loaCode}</li>
            </ol>
        </nav>
        <div class="btn-group shadow-sm">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-download me-1"></i>Download PDF
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{$printIdUrl}" target="_blank">
                        <i class="fas fa-flag me-2 text-danger"></i>Bahasa Indonesia
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{$printEnUrl}" target="_blank">
                        <i class="fas fa-flag-usa me-2 text-primary"></i>English Version
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Language toggle -->
    <div class="text-center mb-4 no-print">
        <div class="btn-group shadow-sm" role="group">
            <a href="{$viewIdUrl}" class="btn {$btnId}">
                <i class="fas fa-flag me-1"></i>Bahasa Indonesia
            </a>
            <a href="{$viewEnUrl}" class="btn {$btnEn}">
                <i class="fas fa-flag-usa me-1"></i>English
            </a>
        </div>
    </div>

    <!-- Certificate -->
    <div class="loa-certificate">
        <div class="loa-inner">
            {$content}
        </div>
    </div>

    <!-- Bottom actions -->
    <div class="text-center mt-4 no-print">
        <div class="btn-group shadow-sm flex-wrap">
            <a href="{$searchUrl}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>{$backLabel}
            </a>
            <a href="{$verifyUrl}" class="btn btn-outline-warning">
                <i class="fas fa-shield-alt me-1"></i>{$verifyLabel}
            </a>
            <button class="btn btn-outline-info" onclick="window.print()">
                <i class="fas fa-print me-1"></i>{$printLabel}
            </button>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;

        return response($html)->header('Content-Type', 'text/html');
    }

    public function widget($loaCode)
    {
        $loa = LoaValidated::where('loa_code', $loaCode)
            ->with(['loaRequest.journal.publisher'])
            ->first();

        $appUrl = rtrim(config('app.url'), '/');

        if (!$loa) {
            $js = <<<JS
(function(){
  var d=document.createElement('div');
  d.style.cssText='display:inline-flex;align-items:center;gap:8px;background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:8px 14px;font-family:sans-serif;font-size:13px;color:#991B1B';
  d.innerHTML='<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> LOA tidak ditemukan: {$loaCode}';
  document.currentScript.parentNode.insertBefore(d,document.currentScript);
})();
JS;
            return response($js, 404)->header('Content-Type', 'application/javascript');
        }

        $req        = $loa->loaRequest;
        $journal    = $req?->journal;
        $publisher  = $journal?->publisher;

        $loaCodeSafe    = addslashes($loa->loa_code);
        $articleSafe    = addslashes(\Illuminate\Support\Str::limit($req?->article_title ?? '', 60));
        $authorSafe     = addslashes($req?->author ?? '');
        $journalSafe    = addslashes($journal?->name ?? '');
        $publisherSafe  = addslashes($publisher?->name ?? '');
        $approvedDate   = $req?->approved_at ? $req->approved_at->format('d M Y') : $loa->created_at->format('d M Y');
        $verifyUrl      = $appUrl . '/loa/view/' . $loaCode;

        $js = <<<JS
(function(){
  var s=document.createElement('style');
  s.textContent='.loa-badge{display:inline-block;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;text-decoration:none;border-radius:10px;border:1.5px solid #BBF7D0;background:#F0FDF4;padding:12px 16px;max-width:360px;color:inherit}.loa-badge:hover{box-shadow:0 4px 12px rgba(0,0,0,.1);transform:translateY(-1px);transition:all .2s}.loa-badge-head{display:flex;align-items:center;gap:8px;margin-bottom:8px}.loa-badge-seal{width:32px;height:32px;background:linear-gradient(135deg,#16A34A,#059669);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}.loa-badge-seal svg{width:16px;height:16px;color:white;stroke:white}.loa-badge-title{font-size:11px;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.5px}.loa-badge-code{font-size:13px;font-weight:800;color:#15803D;font-family:monospace;margin-bottom:4px}.loa-badge-article{font-size:12px;color:#374151;margin-bottom:4px;line-height:1.4}.loa-badge-meta{font-size:11px;color:#6B7280;margin-bottom:2px}.loa-badge-footer{display:flex;align-items:center;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px solid #BBF7D0}.loa-badge-date{font-size:10px;color:#6B7280}.loa-badge-verify{font-size:10px;font-weight:600;color:#16A34A;text-decoration:none}';
  document.head.appendChild(s);
  var a=document.createElement('a');
  a.href='{$verifyUrl}';
  a.target='_blank';
  a.className='loa-badge';
  a.innerHTML='<div class="loa-badge-head"><div class="loa-badge-seal"><svg viewBox="0 0 24 24" fill="none" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div><div><div class="loa-badge-title">Letter of Acceptance</div></div></div><div class="loa-badge-code">{$loaCodeSafe}</div><div class="loa-badge-article">{$articleSafe}</div><div class="loa-badge-meta"><strong>Penulis:</strong> {$authorSafe}</div><div class="loa-badge-meta"><strong>Jurnal:</strong> {$journalSafe}</div><div class="loa-badge-meta"><strong>Publisher:</strong> {$publisherSafe}</div><div class="loa-badge-footer"><span class="loa-badge-date">Disetujui: {$approvedDate}</span><span class="loa-badge-verify">✓ Terverifikasi</span></div>';
  document.currentScript.parentNode.insertBefore(a,document.currentScript);
})();
JS;

        return response($js)->header('Content-Type', 'application/javascript');
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
