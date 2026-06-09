@php
    $isId        = ($lang === 'id');
    $req         = $request ?? null;
    $loaCode     = $loa->loa_code;
    $artTitle    = $req?->article_title ?? '-';
    $author      = $req?->author        ?? '-';
    $email       = $req?->author_email  ?? '-';
    $journalName = $journal?->name      ?? '';
    $eIssn       = $journal?->e_issn    ?? '';
    $pIssn       = $journal?->p_issn    ?? '';
    $volume      = $req?->volume        ?? '-';
    $number      = $req?->number        ?? '-';
    $month       = $req?->month         ?? '-';
    $year        = $req?->year          ?? '-';
    $noReg       = $req?->no_reg        ?? '-';
    $pubName     = $publisher?->name    ?? '';
    $pubEmail    = $publisher?->email   ?? '';
    $pubPhone    = $publisher?->phone   ?? '';
    $chiefEditor = $journal?->chief_editor ?? ($isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief');

    $rawDate = $req?->approved_at ?? $loa->created_at;
    $mnId = ['','Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];
    $approvalDate = $isId
        ? ($rawDate->format('d').' '.$mnId[(int)$rawDate->format('n')].' '.$rawDate->format('Y'))
        : $rawDate->format('d F Y');

    $pubLogo  = $publisher?->logo       ? asset('storage/'.$publisher->logo)      : null;
    $jrnLogo  = $journal?->logo         ? asset('storage/'.$journal->logo)        : null;
    $stamp    = $journal?->ttd_stample  ? asset('storage/'.$journal->ttd_stample) : null;

    $viewIdUrl  = route('loa.view',  [$loaCode, 'id']);
    $viewEnUrl  = route('loa.view',  [$loaCode, 'en']);
    $printIdUrl = route('loa.print', [$loaCode, 'id']);
    $printEnUrl = route('loa.print', [$loaCode, 'en']);
    $verifyUrl  = route('loa.verify.result', $loaCode);
    $qrUrl      = route('qr.code', $loaCode);
    $searchUrl  = route('loa.validated');
    $homeUrl    = route('home');

    $certTitle = $isId ? 'SURAT PERSETUJUAN NASKAH' : 'LETTER OF ACCEPTANCE';
    $certSub   = $isId ? '(Letter of Acceptance)'   : '(Surat Persetujuan Naskah)';
    $nowStr    = now()->format('d F Y, H:i');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $isId ? 'Surat Persetujuan Naskah' : 'Letter of Acceptance' }} – {{ $loaCode }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { background: #e8ecf2; font-family: 'Segoe UI', system-ui, sans-serif; color: #1a1a2e; }
.wrap { max-width: 800px; margin: 0 auto; padding: 20px 12px 40px; }

/* Document card */
.doc { background: #fff; box-shadow: 0 6px 28px rgba(0,0,0,.13); border: 1.5px solid #c9c9d0; border-radius: 4px; overflow: hidden; animation: fadeUp .4s ease; }
@keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:none; } }

/* Header */
.doc-hdr { background: linear-gradient(135deg,#1e3a6e 0%,#2a5298 100%); color:#fff; padding:14px 24px; }
.hdr-inner { display:flex; align-items:center; gap:14px; }
.hdr-logo img { max-height:58px; max-width:58px; object-fit:contain; border-radius:5px; }
.hdr-logo-ph { width:50px; height:50px; border-radius:50%; border:1.5px solid rgba(255,255,255,.3); display:flex; align-items:center; justify-content:center; font-size:8px; color:rgba(255,255,255,.5); flex-shrink:0; }
.hdr-text { flex:1; text-align:center; }
.hdr-pub { font-size:15px; font-weight:700; letter-spacing:.3px; }
.hdr-jrn { font-size:11px; color:rgba(255,255,255,.82); margin-top:2px; }
.hdr-issn { font-size:10px; color:rgba(255,255,255,.62); margin-top:2px; }
.hdr-ct { font-size:9.5px; color:rgba(255,255,255,.55); margin-top:2px; }

/* Gold line */
.gold-line { height:3px; background:linear-gradient(to right,#c9a84c,#e8c96a,#c9a84c); }

/* Title */
.doc-title-band { text-align:center; padding:12px 24px 10px; background:#f5f7fb; border-bottom:1px solid #dde3ec; }
.title-main { font-size:17px; font-weight:800; color:#1e3a6e; letter-spacing:.7px; text-transform:uppercase; }
.title-sub { font-size:11px; color:#6c757d; margin-top:3px; }
.loa-pill { display:inline-block; background:#1e3a6e; color:#fff; font-size:11px; font-weight:700; padding:3px 16px; border-radius:30px; margin-top:6px; letter-spacing:.5px; }

/* Body */
.doc-body { padding:20px 28px; }

/* Recipient */
.to-lbl { font-size:12px; color:#6c757d; margin-bottom:2px; }
.author-name { font-size:15px; font-weight:700; }
.author-email { font-size:12px; color:#888; margin-bottom:14px; }

/* Article title */
.art-box { background:#eef2fb; border-left:4px solid #1e3a6e; padding:10px 14px; margin-bottom:12px; border-radius:0 4px 4px 0; }
.art-lbl { font-size:10px; text-transform:uppercase; letter-spacing:.6px; color:#6c757d; margin-bottom:4px; }
.art-title { font-size:13px; font-weight:600; color:#1e3a6e; line-height:1.5; }

/* Accepted */
.accepted { display:flex; align-items:center; gap:12px; background:#e9f7ef; border:1px solid #a3d9b1; border-left:5px solid #28a745; padding:10px 14px; border-radius:0 4px 4px 0; margin-bottom:12px; }
.accepted-icon { font-size:22px; color:#28a745; }
.accepted-main { font-size:13px; font-weight:700; color:#155724; }
.accepted-sub { font-size:11px; color:#388e3c; }

/* Statement */
.stmt { font-size:13px; color:#374151; line-height:1.7; text-align:justify; margin-bottom:14px; padding:10px 14px; background:#fafafa; border-left:3px solid #c9a84c; border-radius:0 4px 4px 0; }

/* Info table */
.info-tbl { width:100%; border-collapse:collapse; margin-bottom:16px; }
.info-tbl tr { border-bottom:1px solid #f0f2f5; }
.info-tbl td { padding:5px 8px; font-size:13px; vertical-align:top; }
.info-tbl td:first-child { color:#6c757d; font-weight:600; white-space:nowrap; width:38%; }
.val-jrn { font-weight:700; color:#1e3a6e; }
.val-date { font-weight:700; color:#1a6630; }
.val-reg { background:#eef2f7; padding:1px 7px; border-radius:4px; font-size:12px; font-family:monospace; }

/* Signature */
.sig-wrap { display:flex; justify-content:flex-end; margin-top:8px; }
.sig-box { text-align:center; min-width:200px; }
.sig-date { font-size:12px; color:#495057; margin-bottom:3px; }
.sig-role { font-size:12px; font-weight:600; color:#1e3a6e; margin-bottom:8px; }
.sig-img { max-height:88px; max-width:180px; object-fit:contain; display:block; margin:0 auto 6px; }
.sig-blank { height:78px; border-bottom:1.5px solid #1a1a2e; margin:0 16px; }
.sig-name { display:inline-block; font-size:13px; font-weight:700; border-top:1.5px solid #1a1a2e; padding-top:5px; min-width:180px; }
.sig-title { font-size:11.5px; color:#6c757d; margin-top:2px; }

/* Footer */
.doc-footer { background:#1e3a6e; color:#fff; padding:14px 24px; }
.footer-inner { display:flex; align-items:center; gap:18px; }
.qr-wrap img { width:80px; height:80px; border:2px solid rgba(255,255,255,.3); border-radius:4px; background:#fff; padding:2px; display:block; }
.qr-ph { width:80px; height:80px; border:1px dashed rgba(255,255,255,.4); display:flex; align-items:center; justify-content:center; font-size:9px; color:rgba(255,255,255,.4); }
.qr-lbl { font-size:9px; color:rgba(255,255,255,.6); text-align:center; margin-top:3px; }
.verify { flex:1; font-size:11px; }
.verify-title { font-weight:700; color:#c9a84c; font-size:12px; margin-bottom:4px; }
.verify-url { color:rgba(255,255,255,.75); font-size:10px; word-break:break-all; margin-bottom:2px; }
.verify-note { font-size:9px; color:rgba(255,255,255,.45); margin-top:4px; }
.valid-badge { display:inline-flex; align-items:center; gap:5px; background:#28a745; color:#fff; font-size:11px; font-weight:700; padding:5px 12px; border-radius:20px; white-space:nowrap; }

/* Print */
@media print {
  body { background:#fff; }
  .no-print { display:none !important; }
  .wrap { padding:0; max-width:100%; }
  .doc { box-shadow:none; border-radius:0; animation:none; }
}
@media (max-width:600px) {
  .hdr-inner { flex-direction:column; text-align:center; }
  .doc-body { padding:16px; }
  .footer-inner { flex-direction:column; text-align:center; }
  .sig-wrap { justify-content:center; }
}
</style>
</head>
<body>
<div class="wrap">

  {{-- ── ACTION BAR ── --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 no-print">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="{{ $homeUrl }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Home</a></li>
        <li class="breadcrumb-item"><a href="{{ $searchUrl }}" class="text-decoration-none text-muted">{{ $isId ? 'Cari LOA' : 'Search LOA' }}</a></li>
        <li class="breadcrumb-item active">{{ $loaCode }}</li>
      </ol>
    </nav>
    <div class="d-flex gap-2 flex-wrap">
      <div class="btn-group btn-group-sm shadow-sm">
        <a href="{{ $viewIdUrl }}" class="btn {{ $lang==='id' ? 'btn-primary' : 'btn-outline-primary' }}">
          <i class="fas fa-flag me-1"></i>Indonesia
        </a>
        <a href="{{ $viewEnUrl }}" class="btn {{ $lang==='en' ? 'btn-primary' : 'btn-outline-primary' }}">
          <i class="fas fa-flag-usa me-1"></i>English
        </a>
      </div>
      <div class="btn-group btn-group-sm shadow-sm">
        <a href="{{ $printIdUrl }}" target="_blank" class="btn btn-success">
          <i class="fas fa-file-pdf me-1"></i>PDF Indonesia
        </a>
        <a href="{{ $printEnUrl }}" target="_blank" class="btn btn-outline-success">
          <i class="fas fa-file-pdf me-1"></i>PDF English
        </a>
      </div>
      <button class="btn btn-sm btn-outline-secondary shadow-sm" onclick="window.print()">
        <i class="fas fa-print me-1"></i>Print
      </button>
    </div>
  </div>

  {{-- ── DOCUMENT ── --}}
  <div class="doc">

    {{-- Header --}}
    <div class="doc-hdr">
      <div class="hdr-inner">
        <div class="hdr-logo">
          @if($pubLogo)
            <img src="{{ $pubLogo }}" alt="{{ $pubName }}">
          @else
            <div class="hdr-logo-ph"><i class="fas fa-building"></i></div>
          @endif
        </div>
        <div class="hdr-text">
          <div class="hdr-pub">{{ strtoupper($pubName ?: 'PUBLISHER') }}</div>
          @if($journalName)
            <div class="hdr-jrn">{{ $journalName }}</div>
          @endif
          @if($eIssn || $pIssn)
            <div class="hdr-issn">
              @if($eIssn) E-ISSN: {{ $eIssn }} @endif
              @if($eIssn && $pIssn) &nbsp;|&nbsp; @endif
              @if($pIssn) P-ISSN: {{ $pIssn }} @endif
            </div>
          @endif
          @if($pubEmail || $pubPhone)
            <div class="hdr-ct">
              @if($pubEmail) {{ $pubEmail }} @endif
              @if($pubEmail && $pubPhone) &nbsp;|&nbsp; @endif
              @if($pubPhone) {{ $pubPhone }} @endif
            </div>
          @endif
        </div>
        <div class="hdr-logo">
          @if($jrnLogo)
            <img src="{{ $jrnLogo }}" alt="{{ $journalName }}">
          @else
            <div class="hdr-logo-ph"><i class="fas fa-book"></i></div>
          @endif
        </div>
      </div>
    </div>

    {{-- Gold line --}}
    <div class="gold-line"></div>

    {{-- Title --}}
    <div class="doc-title-band">
      <div class="title-main">{{ $certTitle }}</div>
      <div class="title-sub">{{ $certSub }}</div>
      <div><span class="loa-pill"><i class="fas fa-qrcode me-1"></i>{{ $loaCode }}</span></div>
    </div>

    {{-- Body --}}
    <div class="doc-body">

      <div class="to-lbl">{{ $isId ? 'Kepada Yth.' : 'To' }}:</div>
      <div class="author-name">{{ $author }}</div>
      <div class="author-email"><i class="fas fa-envelope me-1 text-muted"></i>{{ $email }}</div>

      <div class="art-box">
        <div class="art-lbl"><i class="fas fa-file-alt me-1"></i>{{ $isId ? 'Judul Artikel' : 'Article Title' }}</div>
        <div class="art-title">"{{ $artTitle }}"</div>
      </div>

      <div class="accepted">
        <div class="accepted-icon"><i class="fas fa-check-circle"></i></div>
        <div>
          <div class="accepted-main">{{ $isId ? 'DITERIMA UNTUK DIPUBLIKASIKAN' : 'ACCEPTED FOR PUBLICATION' }}</div>
          <div class="accepted-sub">{{ $isId ? 'Has Been Accepted for Publication' : 'Telah Diterima untuk Dipublikasikan' }}</div>
        </div>
      </div>

      <div class="stmt">
        @if($isId)
          Dengan ini dinyatakan bahwa naskah artikel ilmiah di atas telah melalui proses <strong>review</strong>
          oleh Mitra Bebestari dan diputuskan <strong>DITERIMA</strong> untuk dipublikasikan pada jurnal
          <strong>{{ $journalName }}</strong> edisi Volume {{ $volume }}, Nomor {{ $number }},
          {{ $month }} {{ $year }}.
          Surat persetujuan ini merupakan bukti resmi penerimaan naskah yang sah untuk keperluan
          akademik dan profesional.
        @else
          This is to certify that the manuscript above has completed the <strong>peer-review</strong> process
          and has been officially <strong>ACCEPTED</strong> for publication in
          <strong>{{ $journalName }}</strong>, Volume {{ $volume }}, Number {{ $number }},
          {{ $month }} {{ $year }}.
          This letter constitutes official proof of acceptance for academic and professional purposes.
        @endif
      </div>

      <table class="info-tbl">
        <tr>
          <td><i class="fas fa-book text-primary me-2"></i>{{ $isId ? 'Nama Jurnal' : 'Journal Name' }}</td>
          <td class="val-jrn">{{ $journalName }}</td>
        </tr>
        <tr>
          <td><i class="fas fa-layer-group text-info me-2"></i>{{ $isId ? 'Volume / Nomor' : 'Volume / Number' }}</td>
          <td>Vol.&nbsp;{{ $volume }}&nbsp;/&nbsp;No.&nbsp;{{ $number }}</td>
        </tr>
        <tr>
          <td><i class="fas fa-calendar text-warning me-2"></i>{{ $isId ? 'Edisi Terbit' : 'Issue' }}</td>
          <td>{{ $month }} {{ $year }}</td>
        </tr>
        <tr>
          <td><i class="fas fa-hashtag text-secondary me-2"></i>{{ $isId ? 'No. Registrasi' : 'Registration No.' }}</td>
          <td><span class="val-reg">{{ $noReg }}</span></td>
        </tr>
        <tr>
          <td><i class="fas fa-check-circle text-success me-2"></i>{{ $isId ? 'Tanggal Disetujui' : 'Approval Date' }}</td>
          <td class="val-date">{{ $approvalDate }}</td>
        </tr>
      </table>

      <div class="sig-wrap">
        <div class="sig-box">
          <div class="sig-date">{{ $approvalDate }}</div>
          <div class="sig-role">{{ $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>
          @if($stamp)
            <img src="{{ $stamp }}" class="sig-img" alt="Tanda tangan">
          @else
            <div class="sig-blank"></div>
          @endif
          <div><span class="sig-name">{{ $chiefEditor }}</span></div>
          <div class="sig-title">{{ $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>
        </div>
      </div>

    </div>{{-- /doc-body --}}

    {{-- Footer --}}
    <div class="doc-footer">
      <div class="footer-inner">
        <div class="qr-wrap">
          <img src="{{ $qrUrl }}" alt="QR {{ $loaCode }}" onerror="this.parentNode.innerHTML='<div class=qr-ph>QR</div>'">
          <div class="qr-lbl">{{ $isId ? 'Scan untuk verifikasi' : 'Scan to verify' }}</div>
        </div>
        <div class="verify">
          <div class="verify-title"><i class="fas fa-shield-alt me-1"></i>{{ $isId ? 'Verifikasi Dokumen' : 'Document Verification' }}</div>
          <div class="verify-url">{{ $verifyUrl }}</div>
          <div class="verify-url">{{ $isId ? 'Kode LOA' : 'LOA Code' }}: <strong style="color:#fff;">{{ $loaCode }}</strong></div>
          <div class="verify-note">{{ $isId ? 'Dilihat pada' : 'Viewed on' }}: {{ $nowStr }}</div>
        </div>
        <div>
          <span class="valid-badge"><i class="fas fa-check-circle"></i>{{ $isId ? 'TERVALIDASI' : 'VALIDATED' }}</span>
        </div>
      </div>
    </div>

  </div>{{-- /doc --}}

  {{-- Bottom actions --}}
  <div class="text-center mt-3 no-print">
    <a href="{{ $searchUrl }}" class="btn btn-sm btn-outline-secondary me-1">
      <i class="fas fa-arrow-left me-1"></i>{{ $isId ? 'Kembali ke Pencarian' : 'Back to Search' }}
    </a>
    <a href="{{ $verifyUrl }}" class="btn btn-sm btn-outline-warning">
      <i class="fas fa-shield-alt me-1"></i>{{ $isId ? 'Verifikasi LOA Lain' : 'Verify Another LOA' }}
    </a>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
