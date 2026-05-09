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

        $loaRequest  = $loaValidated->loaRequest;
        $journal     = $loaRequest?->journal;
        $publisher   = $journal?->publisher;
        $publisherId = $journal?->publisher_id ?? null;

        $template = LoaTemplate::getDefault($lang, $publisherId);

        $data = [
            'loa'      => $loaValidated,
            'request'  => $loaRequest,
            'journal'  => $journal,
            'publisher'=> $publisher,
            'lang'     => $lang,
            'template' => $template,
        ];

        // Always render the professional certificate view
        return $this->renderDynamicTemplate($template, $data);
    }

    private function renderDynamicTemplate($template, $data)
    {
        // Extract data objects directly — avoids template variable mismatch issues
        $lang        = $data['lang'];
        $loa         = $data['loa'];
        $req         = $data['request'];
        $journal     = $data['journal'];
        $publisher   = $data['publisher'];

        // Pre-compute display values (null-safe — any relationship may be absent)
        $loaCode        = $loa->loa_code;
        $articleTitle   = $req?->article_title   ?? '-';
        $authorName     = $req?->author           ?? '-';
        $authorEmail    = $req?->author_email     ?? '-';
        $volume         = $req?->volume           ?? '-';
        $number         = $req?->number           ?? '-';
        $monthYear      = trim(($req?->month ?? '') . ' ' . ($req?->year ?? ''));
        $noReg          = $req?->no_reg           ?? '-';
        $reqApprovedAt  = $req?->approved_at;
        $approvalDate   = $reqApprovedAt ? $reqApprovedAt->format('d F Y') : $loa->created_at->format('d F Y');
        $createdAt      = $loa->created_at->format('d F Y, H:i');
        $publisherName  = $publisher?->name       ?? '';
        $publisherAddr  = $publisher?->address    ?? '';
        $publisherEmail = $publisher?->email      ?? '';
        $publisherPhone = $publisher?->phone      ?? '';
        $journalName    = $journal?->name         ?? '';
        $eIssn          = $journal?->e_issn       ?? '';
        $pIssn          = $journal?->p_issn       ?? '';
        $chiefEditor    = $journal?->chief_editor ?? ($lang === 'id' ? 'Pemimpin Redaksi' : 'Editor-in-Chief');

        // Logo HTML (null-safe)
        $pubLogo     = $publisher?->logo;
        $pubLogoHtml = $pubLogo
            ? '<img src="' . asset('storage/' . $pubLogo) . '" alt="' . e($publisherName) . '" style="max-height:72px;max-width:120px;object-fit:contain;background:rgba(255,255,255,.15);padding:8px;border-radius:8px;">'
            : '<div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;"><i class="fas fa-building fa-2x" style="color:rgba(255,255,255,.7);"></i></div>';

        $jrnLogo     = $journal?->logo;
        $jrnLogoHtml = $jrnLogo
            ? '<img src="' . asset('storage/' . $jrnLogo) . '" alt="' . e($journalName) . '" style="max-height:72px;max-width:120px;object-fit:contain;background:rgba(255,255,255,.15);padding:8px;border-radius:8px;">'
            : '<div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;"><i class="fas fa-book fa-2x" style="color:rgba(255,255,255,.7);"></i></div>';

        $ttdStamp    = $journal?->ttd_stample;
        $stampHtml   = $ttdStamp
            ? '<img src="' . asset('storage/' . $ttdStamp) . '" alt="Signature" style="max-height:100px;max-width:180px;object-fit:contain;">'
            : '<div style="border:2px dashed #ccc;border-radius:8px;padding:20px;color:#aaa;text-align:center;min-height:90px;display:flex;align-items:center;justify-content:center;flex-direction:column;"><i class="fas fa-signature fa-2x mb-1"></i><small>' . ($lang === 'id' ? 'Tanda Tangan & Stempel' : 'Signature & Stamp') . '</small></div>';

        $issnHtml = '';
        if ($eIssn) $issnHtml .= '<span style="display:inline-block;background:#198754;color:#fff;font-size:.75rem;padding:2px 8px;border-radius:20px;margin-right:4px;">E-ISSN: ' . e($eIssn) . '</span>';
        if ($pIssn) $issnHtml .= '<span style="display:inline-block;background:#0dcaf0;color:#000;font-size:.75rem;padding:2px 8px;border-radius:20px;">P-ISSN: ' . e($pIssn) . '</span>';
        if (!$issnHtml) $issnHtml = '<span style="color:#999;">-</span>';

        $contactHtml = '';
        if ($publisherEmail) $contactHtml .= '<span><i class="fas fa-envelope me-1"></i>' . e($publisherEmail) . '</span>';
        if ($publisherPhone) $contactHtml .= '<span class="mx-2">|</span><span><i class="fas fa-phone me-1"></i>' . e($publisherPhone) . '</span>';

        // Localized labels
        $isId = ($lang === 'id');
        $certTitle      = $isId ? 'SURAT PERSETUJUAN NASKAH' : 'LETTER OF ACCEPTANCE';
        $certSub        = $isId ? '(LETTER OF ACCEPTANCE)' : '(SURAT PERSETUJUAN NASKAH)';
        $labelArticle   = $isId ? 'Informasi Artikel' : 'Article Information';
        $labelTitle     = $isId ? 'Judul Artikel' : 'Article Title';
        $labelAuthor    = $isId ? 'Penulis' : 'Author(s)';
        $labelEmail     = $isId ? 'Email Penulis' : 'Author Email';
        $labelJournal   = $isId ? 'Jurnal' : 'Journal';
        $labelVol       = $isId ? 'Volume / Nomor' : 'Volume / Number';
        $labelEdition   = $isId ? 'Edisi' : 'Issue';
        $labelReg       = $isId ? 'No. Registrasi' : 'Registration No.';
        $labelApproved  = $isId ? 'Tanggal Persetujuan' : 'Approval Date';
        $labelVerifyQr  = $isId ? 'Verifikasi QR' : 'QR Verification';
        $labelScanVerif = $isId ? 'Scan untuk verifikasi' : 'Scan to verify';
        $labelDocInfo   = $isId ? 'Informasi Dokumen' : 'Document Info';
        $labelCreated   = $isId ? 'Dibuat' : 'Created';
        $labelViewed    = $isId ? 'Dilihat' : 'Viewed';
        $labelValidated = $isId ? 'TERVALIDASI' : 'VALIDATED';
        $labelAccepted  = $isId ? 'TELAH DITERIMA UNTUK DIPUBLIKASIKAN' : 'HAS BEEN ACCEPTED FOR PUBLICATION';
        $labelAccSub    = $isId ? '(HAS BEEN ACCEPTED FOR PUBLICATION)' : '(TELAH DITERIMA UNTUK DIPUBLIKASIKAN)';
        $labelEditorRole = $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief';
        $labelVerifyDoc  = $isId ? 'Verifikasi Dokumen' : 'Document Verification';
        $labelVerifyInst = $isId ? 'Untuk memverifikasi keaslian dokumen ini:' : 'To verify the authenticity of this document:';
        $labelVisit      = $isId ? 'Kunjungi' : 'Visit';
        $labelLoaCode    = $isId ? 'Kode LOA' : 'LOA Code';
        $labelSysInfo    = $isId ? 'Informasi Sistem' : 'System Information';
        $labelSysGen     = $isId ? 'Dokumen dibuat otomatis oleh:' : 'Document automatically generated by:';
        $labelPrinted    = $isId ? 'Dicetak pada' : 'Generated on';
        $defaultBody     = $isId
            ? "<p>Naskah artikel ilmiah telah melalui proses <strong>review</strong> dan memenuhi persyaratan untuk dipublikasikan pada jurnal <strong>" . e($journalName) . "</strong>.</p><p style='color:#6c757d;font-size:.93rem;'>Surat persetujuan ini merupakan bukti resmi penerimaan naskah untuk publikasi dan dapat digunakan untuk keperluan akademik dan profesional.</p>"
            : "<p>The scientific article manuscript has undergone the <strong>review process</strong> and has met the requirements for publication in <strong>" . e($journalName) . "</strong>.</p><p style='color:#6c757d;font-size:.93rem;'>This letter of acceptance serves as official proof of manuscript acceptance for publication and can be used for academic and professional purposes.</p>";

        // Always use the professional default statement — data is in the info table above.
        // The custom template body is for PDF/print only; the web view uses structured layout.
        $statementHtml = $defaultBody;

        // URLs
        $homeUrl    = route('home');
        $searchUrl  = route('loa.validated');
        $viewIdUrl  = route('loa.view', [$loaCode, 'id']);
        $viewEnUrl  = route('loa.view', [$loaCode, 'en']);
        $printIdUrl = route('loa.print', [$loaCode, 'id']);
        $printEnUrl = route('loa.print', [$loaCode, 'en']);
        $verifyUrl  = route('loa.verify');
        $qrUrl      = route('qr.code', $loaCode);
        $pageTitle  = $isId ? 'Surat Persetujuan Naskah' : 'Letter of Acceptance';
        $homeLabel  = $isId ? 'Beranda' : 'Home';
        $searchLabel = $isId ? 'Cari LOA' : 'Search LOA';
        $backLabel  = $isId ? 'Kembali ke Pencarian' : 'Back to Search';
        $verifyLabel = $isId ? 'Verifikasi LOA Lain' : 'Verify Other LOA';
        $printLabel = $isId ? 'Cetak Halaman' : 'Print Page';
        $btnId      = $isId ? 'btn-primary' : 'btn-outline-primary';
        $btnEn      = !$isId ? 'btn-primary' : 'btn-outline-primary';
        $nowStr     = now()->format('d F Y, H:i:s');

        $html = <<<HTML
<!DOCTYPE html>
<html lang="{$lang}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$pageTitle} – {$loaCode}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body{background:#eef2f7;font-family:'Segoe UI',system-ui,sans-serif;color:#212529;}
.loa-page{max-width:960px;margin:0 auto;padding:2rem 1rem 4rem;}
.cert-card{border-radius:20px;overflow:hidden;box-shadow:0 12px 48px rgba(0,0,0,.16);animation:fadeUp .5s ease-out;}
@keyframes fadeUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}
.cert-header{background:linear-gradient(135deg,#1e3c72 0%,#2a5298 60%,#764ba2 100%);color:#fff;padding:2rem;}
.cert-header-grid{display:grid;grid-template-columns:auto 1fr auto;gap:1.5rem;align-items:center;}
.cert-pub-info h2{font-size:1.3rem;font-weight:700;margin-bottom:.3rem;}
.cert-pub-info p{font-size:.85rem;opacity:.85;margin:0;}
.cert-title-block{text-align:center;margin-top:1.2rem;border-top:1px solid rgba(255,255,255,.25);padding-top:1.2rem;}
.cert-title-block h3{font-size:1.5rem;font-weight:800;letter-spacing:1px;margin:0;}
.cert-title-block small{opacity:.75;font-size:.9rem;}
.loa-code-bar{background:#fff;border-top:4px solid #1e3c72;border-bottom:1px solid #dee2e6;padding:.9rem 1.5rem;text-align:center;}
.loa-code-bar span{font-size:1.2rem;font-weight:700;color:#1e3c72;letter-spacing:1px;}
.cert-body{background:#fff;padding:2rem 2.5rem;}
.info-table td{padding:.55rem .75rem;border-bottom:1px solid #f0f2f5;vertical-align:top;}
.info-table td:first-child{color:#6c757d;font-weight:600;white-space:nowrap;width:36%;}
.qr-box{background:#f8f9fa;border:2px solid #dee2e6;border-radius:12px;padding:1rem;text-align:center;}
.qr-box img{width:140px;height:140px;object-fit:contain;}
.accepted-banner{background:linear-gradient(135deg,#d4edda,#a8dfb5);border:none;border-radius:12px;padding:1.5rem;text-align:center;margin:1.5rem 0;}
.accepted-banner h3{color:#155724;font-weight:800;font-size:1.4rem;margin-bottom:.3rem;}
.accepted-banner small{color:#218838;}
.sig-section{text-align:center;padding-top:1.5rem;}
.sig-section .sig-label{color:#6c757d;font-size:.9rem;margin-bottom:.5rem;}
.sig-name{border-top:2px solid #212529;padding-top:.5rem;margin-top:.5rem;font-weight:700;}
.cert-footer{background:#f8f9fa;border-top:1px solid #dee2e6;padding:1.5rem 2.5rem;}
.verified-badge{display:inline-flex;align-items:center;gap:.4rem;background:#d4edda;color:#155724;border-radius:20px;padding:.35rem .9rem;font-size:.82rem;font-weight:600;}
code.loa{background:#e9ecef;padding:.15rem .5rem;border-radius:4px;font-size:.9rem;}
@media print{
  .no-print{display:none!important;}
  body{background:#fff!important;}
  .loa-page{padding:0;max-width:100%;}
  .cert-card{box-shadow:none;border-radius:0;animation:none;}
}
@media(max-width:600px){
  .cert-header-grid{grid-template-columns:1fr;text-align:center;}
  .cert-body{padding:1.5rem;}
}
</style>
</head>
<body>
<div class="loa-page">

  <!-- Nav bar -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 no-print">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{$homeUrl}" class="text-decoration-none text-secondary"><i class="fas fa-home me-1"></i>{$homeLabel}</a></li>
        <li class="breadcrumb-item"><a href="{$searchUrl}" class="text-decoration-none text-secondary">{$searchLabel}</a></li>
        <li class="breadcrumb-item active fw-semibold">{$loaCode}</li>
      </ol>
    </nav>
    <div class="btn-group shadow-sm">
      <button class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fas fa-file-pdf me-1"></i>Download PDF
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{$printIdUrl}" target="_blank"><i class="fas fa-flag me-2 text-danger"></i>Bahasa Indonesia</a></li>
        <li><a class="dropdown-item" href="{$printEnUrl}" target="_blank"><i class="fas fa-flag-usa me-2 text-primary"></i>English Version</a></li>
      </ul>
    </div>
  </div>

  <!-- Language toggle -->
  <div class="text-center mb-4 no-print">
    <div class="btn-group shadow-sm">
      <a href="{$viewIdUrl}" class="btn {$btnId}"><i class="fas fa-flag me-1"></i>Bahasa Indonesia</a>
      <a href="{$viewEnUrl}" class="btn {$btnEn}"><i class="fas fa-flag-usa me-1"></i>English</a>
    </div>
  </div>

  <!-- Certificate card -->
  <div class="cert-card">

    <!-- Gradient header -->
    <div class="cert-header">
      <div class="cert-header-grid">
        <div>{$pubLogoHtml}</div>
        <div class="cert-pub-info">
          <h2>{$publisherName}</h2>
          <p>{$publisherAddr}</p>
          <p style="margin-top:.3rem;">{$contactHtml}</p>
        </div>
        <div style="text-align:right;">{$jrnLogoHtml}</div>
      </div>
      <div class="cert-title-block">
        <h3><i class="fas fa-certificate me-2"></i>{$certTitle}</h3>
        <small>{$certSub}</small>
      </div>
    </div>

    <!-- LOA code bar -->
    <div class="loa-code-bar">
      <i class="fas fa-qrcode me-2 text-primary"></i>
      <span>{$labelLoaCode}: {$loaCode}</span>
      <span class="verified-badge ms-3"><i class="fas fa-check-circle"></i>{$labelValidated}</span>
    </div>

    <!-- Body -->
    <div class="cert-body">
      <div class="row g-4">

        <!-- Article info -->
        <div class="col-lg-8">
          <div class="card border-0 h-100" style="background:#f8f9fa;border-radius:12px;">
            <div class="card-header border-0 text-white fw-semibold" style="background:#0d6efd;border-radius:12px 12px 0 0;">
              <i class="fas fa-file-alt me-2"></i>{$labelArticle}
            </div>
            <div class="card-body p-0">
              <table class="table table-borderless info-table mb-0">
                <tbody>
                  <tr><td><i class="fas fa-heading text-primary me-2"></i>{$labelTitle}</td><td><strong>{$articleTitle}</strong></td></tr>
                  <tr><td><i class="fas fa-user text-success me-2"></i>{$labelAuthor}</td><td>{$authorName}</td></tr>
                  <tr><td><i class="fas fa-envelope text-warning me-2"></i>{$labelEmail}</td><td>{$authorEmail}</td></tr>
                  <tr><td><i class="fas fa-book text-info me-2"></i>{$labelJournal}</td><td><strong>{$journalName}</strong><br><small>{$issnHtml}</small></td></tr>
                  <tr><td><i class="fas fa-list-ol text-primary me-2"></i>{$labelVol}</td><td>Vol. {$volume} &nbsp; No. {$number}</td></tr>
                  <tr><td><i class="fas fa-calendar text-warning me-2"></i>{$labelEdition}</td><td>{$monthYear}</td></tr>
                  <tr><td><i class="fas fa-hashtag text-secondary me-2"></i>{$labelReg}</td><td><code class="loa">{$noReg}</code></td></tr>
                  <tr><td><i class="fas fa-check-circle text-success me-2"></i>{$labelApproved}</td><td><strong class="text-success">{$approvalDate}</strong></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- QR + status -->
        <div class="col-lg-4 d-flex flex-column gap-3">
          <div class="card border-0" style="background:#fff8e1;border-radius:12px;border:1px solid #ffe082!important;">
            <div class="card-header border-0 fw-semibold" style="background:#ffc107;border-radius:12px 12px 0 0;font-size:.9rem;">
              <i class="fas fa-qrcode me-2"></i>{$labelVerifyQr}
            </div>
            <div class="card-body text-center p-3">
              <div class="qr-box mb-2">
                <img src="{$qrUrl}" alt="QR {$loaCode}" onerror="this.parentNode.innerHTML='<i class=\'fas fa-qrcode fa-3x text-muted\'></i>'">
              </div>
              <small class="text-muted"><i class="fas fa-mobile-alt me-1"></i>{$labelScanVerif}</small>
            </div>
          </div>
          <div class="card border-0" style="background:#f0fff4;border:1px solid #b2dfdb!important;border-radius:12px;">
            <div class="card-header border-0 fw-semibold text-white" style="background:#198754;border-radius:12px 12px 0 0;font-size:.9rem;">
              <i class="fas fa-info-circle me-2"></i>{$labelDocInfo}
            </div>
            <div class="card-body p-3 small">
              <div class="text-center mb-2">
                <span class="badge bg-success py-2 px-3 fs-6"><i class="fas fa-check-circle me-1"></i>{$labelValidated}</span>
              </div>
              <p class="mb-1 text-muted"><strong>{$labelCreated}:</strong><br><i class="fas fa-calendar me-1"></i>{$createdAt}</p>
              <p class="mb-0 text-muted"><strong>{$labelViewed}:</strong><br><i class="fas fa-clock me-1"></i>{$nowStr}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Acceptance banner -->
      <div class="accepted-banner mt-4">
        <h3><i class="fas fa-check-circle me-2"></i>{$labelAccepted}</h3>
        <small>{$labelAccSub}</small>
      </div>

      <!-- Statement text -->
      <div class="text-center mb-4 px-2" style="font-size:1.05rem;line-height:1.8;">
        {$statementHtml}
      </div>

      <!-- Signature -->
      <div class="row justify-content-end mt-4">
        <div class="col-md-5 col-lg-4">
          <div class="sig-section">
            <p class="sig-label">{$approvalDate}</p>
            <p class="sig-label fw-semibold">{$labelEditorRole}</p>
            <div class="mb-3">{$stampHtml}</div>
            <div class="sig-name">
              {$chiefEditor}<br>
              <small class="text-muted">{$labelEditorRole}</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="cert-footer">
      <div class="row gy-3">
        <div class="col-md-6">
          <h6 class="fw-bold text-primary mb-1"><i class="fas fa-shield-alt me-2"></i>{$labelVerifyDoc}</h6>
          <p class="small text-muted mb-1">{$labelVerifyInst}</p>
          <p class="small mb-0">
            <strong>{$labelVisit}:</strong>
            <a href="{$verifyUrl}" class="text-decoration-none" target="_blank">{$verifyUrl}</a><br>
            <strong>{$labelLoaCode}:</strong> <code class="loa">{$loaCode}</code>
          </p>
        </div>
        <div class="col-md-6 text-md-end">
          <h6 class="fw-bold text-secondary mb-1"><i class="fas fa-cog me-2"></i>{$labelSysInfo}</h6>
          <p class="small text-muted mb-1">{$labelSysGen}</p>
          <p class="small mb-0"><strong>LOA Management System</strong><br>{$labelPrinted}: {$nowStr}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom actions -->
  <div class="text-center mt-4 no-print">
    <div class="btn-group shadow-sm flex-wrap">
      <a href="{$searchUrl}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>{$backLabel}</a>
      <a href="{$verifyUrl}" class="btn btn-outline-warning"><i class="fas fa-shield-alt me-1"></i>{$verifyLabel}</a>
      <button class="btn btn-outline-info" onclick="window.print()"><i class="fas fa-print me-1"></i>{$printLabel}</button>
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
