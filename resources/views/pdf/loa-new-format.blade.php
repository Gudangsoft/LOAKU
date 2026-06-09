<!DOCTYPE html>
<html lang="{{ $lang ?? 'id' }}">
<head>
<meta charset="UTF-8">
<title>LOA – {{ isset($loa) ? $loa->loa_code : '' }}</title>
<style>
@page {
    margin: 14mm 16mm 14mm 16mm;
    size: A4 portrait;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 9pt;
    line-height: 1.45;
    color: #1a1a2e;
    background: #fff;
}

/* ── OUTER FRAME ── */
.page-frame {
    border: 2.5pt solid #1e3a6e;
    padding: 0;
}

/* ── HEADER ── */
.hdr {
    background-color: #1e3a6e;
    padding: 9pt 12pt;
}
.hdr table { width: 100%; border-collapse: collapse; margin: 0; }
.hdr td { padding: 0; vertical-align: middle; }
.hdr-logo-cell { width: 58pt; text-align: center; }
.hdr-logo {
    max-width: 50pt;
    max-height: 50pt;
}
.logo-circle {
    width: 46pt; height: 46pt;
    border: 1.5pt solid rgba(255,255,255,0.35);
    border-radius: 50%;
    margin: 0 auto;
    padding-top: 14pt;
    text-align: center;
    font-size: 6pt;
    color: rgba(255,255,255,0.55);
}
.hdr-center { padding: 0 10pt; text-align: center; }
.hdr-pub-name {
    font-size: 13pt;
    font-weight: bold;
    color: #ffffff;
    letter-spacing: 0.5pt;
    margin-bottom: 2pt;
}
.hdr-journal-name {
    font-size: 9pt;
    color: rgba(255,255,255,0.88);
    margin-bottom: 2pt;
}
.hdr-issn {
    font-size: 7.5pt;
    color: rgba(255,255,255,0.70);
    margin-bottom: 1.5pt;
}
.hdr-contact {
    font-size: 7pt;
    color: rgba(255,255,255,0.60);
}

/* ── TITLE BAND ── */
.title-band {
    background-color: #f4f7fb;
    border-top: 3pt solid #c9a84c;
    border-bottom: 1.5pt solid #1e3a6e;
    padding: 7pt 12pt;
    text-align: center;
}
.doc-title {
    font-size: 12pt;
    font-weight: bold;
    color: #1e3a6e;
    letter-spacing: 0.8pt;
    text-transform: uppercase;
}
.doc-sub {
    font-size: 8pt;
    color: #6c757d;
    margin-top: 2pt;
}
.loa-badge {
    display: inline-block;
    background-color: #1e3a6e;
    color: #ffffff;
    font-size: 8pt;
    font-weight: bold;
    padding: 2pt 10pt;
    border-radius: 20pt;
    margin-top: 4pt;
    letter-spacing: 0.5pt;
}

/* ── CONTENT PAD ── */
.body-pad { padding: 8pt 12pt 6pt 12pt; }

/* ── INFO TABLE ── */
.info-tbl { width: 100%; border-collapse: collapse; margin-bottom: 7pt; }
.info-tbl tr { border-bottom: 0.5pt solid #eaecef; }
.info-tbl td { padding: 3pt 5pt; font-size: 8.5pt; vertical-align: top; }
.info-lbl { color: #5a6472; font-weight: bold; width: 33%; white-space: nowrap; }
.info-sep { color: #aaa; width: 6pt; }
.info-val { color: #1a1a2e; }
.article-val { font-weight: bold; color: #1e3a6e; font-size: 9pt; }
.date-val { font-weight: bold; color: #1a6630; }
.reg-val {
    background-color: #eef2f7;
    padding: 0pt 4pt;
    border-radius: 3pt;
    font-size: 8pt;
    font-family: monospace;
}

/* ── ACCEPTED BANNER ── */
.accepted-banner {
    background-color: #e9f7ef;
    border: 1pt solid #a3d9b1;
    border-left: 4pt solid #28a745;
    padding: 6pt 10pt;
    margin-bottom: 6pt;
}
.accepted-main {
    font-size: 10.5pt;
    font-weight: bold;
    color: #155724;
    text-transform: uppercase;
}
.accepted-en {
    font-size: 7.5pt;
    color: #388e3c;
    margin-top: 1pt;
}

/* ── STATEMENT ── */
.statement-box {
    background-color: #f8f9fc;
    border: 0.5pt solid #dee2e6;
    border-left: 3pt solid #c9a84c;
    padding: 5pt 9pt;
    font-size: 8.5pt;
    color: #374151;
    text-align: justify;
    line-height: 1.55;
    margin-bottom: 8pt;
}

/* ── DIVIDER ── */
.divider { height: 1pt; background-color: #dee2e6; margin: 0 0 7pt 0; }

/* ── FOOTER: QR + SIG + VERIFY ── */
.footer-tbl { width: 100%; border-collapse: collapse; }
.footer-tbl td { padding: 0; vertical-align: top; }

.qr-cell {
    width: 22%;
    text-align: center;
    padding-right: 6pt;
    border-right: 0.8pt solid #dee2e6;
}
.qr-img { width: 72pt; height: 72pt; }
.qr-placeholder {
    width: 72pt; height: 72pt;
    border: 1pt dashed #ccc;
    margin: 0 auto;
    padding-top: 26pt;
    text-align: center;
    font-size: 6.5pt;
    color: #aaa;
}
.qr-label { font-size: 6.5pt; color: #888; margin-top: 3pt; }

.sig-cell {
    width: 40%;
    text-align: center;
    padding: 0 10pt;
    border-right: 0.8pt solid #dee2e6;
}
.sig-place { font-size: 8pt; color: #495057; margin-bottom: 3pt; }
.sig-role { font-size: 8pt; font-weight: bold; color: #1e3a6e; margin-bottom: 5pt; }
.sig-img-wrap { height: 58pt; text-align: center; }
.sig-img { max-height: 55pt; max-width: 130pt; }
.sig-line {
    border-bottom: 1.2pt solid #1a1a2e;
    height: 55pt;
    margin: 0 20pt;
}
.sig-name { font-size: 8.5pt; font-weight: bold; color: #1a1a2e; margin-top: 4pt; }
.sig-title { font-size: 7.5pt; color: #6c757d; }
.sig-journal { font-size: 7pt; color: #888; }

.verify-cell {
    width: 38%;
    padding-left: 10pt;
}
.verify-title {
    font-size: 8pt;
    font-weight: bold;
    color: #1e3a6e;
    margin-bottom: 4pt;
}
.verify-text { font-size: 7.5pt; color: #495057; line-height: 1.6; }
.verify-url { font-size: 7pt; color: #1e3a6e; word-break: break-all; }
.validated-chip {
    display: inline-block;
    background-color: #d4edda;
    color: #155724;
    border: 0.5pt solid #a3d9b1;
    border-radius: 20pt;
    padding: 2pt 8pt;
    font-size: 7.5pt;
    font-weight: bold;
    margin-top: 5pt;
}

/* ── BOTTOM BAR ── */
.bottom-bar {
    background-color: #1e3a6e;
    padding: 4pt 10pt;
    text-align: center;
    font-size: 6.5pt;
    color: rgba(255,255,255,0.75);
}
</style>
</head>
<body>

@php
    $isId        = (($lang ?? 'id') === 'id');
    $loaCode     = isset($loa) ? $loa->loa_code : 'N/A';
    $artTitle    = isset($request) ? ($request->article_title ?? '-') : '-';
    $author      = isset($request) ? ($request->author       ?? '-') : '-';
    $email       = isset($request) ? ($request->author_email ?? '-') : '-';
    $jName       = isset($journal)   ? ($journal->name   ?? '-') : '-';
    $eIssn       = isset($journal)   ? ($journal->e_issn ?? '')  : '';
    $pIssn       = isset($journal)   ? ($journal->p_issn ?? '')  : '';
    $vol         = isset($request)   ? ($request->volume  ?? '-') : '-';
    $num         = isset($request)   ? ($request->number  ?? '-') : '-';
    $month       = isset($request)   ? ($request->month   ?? '-') : '-';
    $year        = isset($request)   ? ($request->year    ?? '-') : '-';
    $noReg       = isset($request)   ? ($request->no_reg  ?? '-') : '-';
    $pubName     = isset($publisher) ? ($publisher->name    ?? '') : '';
    $pubAddr     = isset($publisher) ? ($publisher->address ?? '') : '';
    $pubEmail    = isset($publisher) ? ($publisher->email   ?? '') : '';
    $pubPhone    = isset($publisher) ? ($publisher->phone   ?? '') : '';
    $chiefEd     = isset($journal)   ? ($journal->chief_editor ?? ($isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief')) : ($isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief');

    $rawDate = isset($request) && isset($request->approved_at)
               ? $request->approved_at
               : (isset($loa) ? $loa->created_at : null);
    if ($rawDate) {
        if ($isId) {
            $mnId = ['','Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];
            $approvalDate = $rawDate->format('d') . ' ' . $mnId[(int)$rawDate->format('n')] . ' ' . $rawDate->format('Y');
        } else {
            $approvalDate = $rawDate->format('d F Y');
        }
    } else {
        $approvalDate = '-';
    }

    $pubLogoPath  = isset($publisher) && !empty($publisher->logo)       ? public_path('storage/' . $publisher->logo)       : null;
    $jrnLogoPath  = isset($journal)   && !empty($journal->logo)         ? public_path('storage/' . $journal->logo)         : null;
    $stampPath    = isset($journal)   && !empty($journal->ttd_stample)  ? public_path('storage/' . $journal->ttd_stample)  : null;

    $verifyUrl    = route('loa.verify.result', $loaCode);
    $nowStr       = now()->format('d F Y, H:i');

    $certTitle    = $isId ? 'SURAT PERSETUJUAN NASKAH' : 'LETTER OF ACCEPTANCE';
    $certSub      = $isId ? '(Letter of Acceptance)'   : '(Surat Persetujuan Naskah)';

    $issnStr = '';
    if ($eIssn) $issnStr .= 'E-ISSN: ' . $eIssn;
    if ($eIssn && $pIssn) $issnStr .= '   |   ';
    if ($pIssn) $issnStr .= 'P-ISSN: ' . $pIssn;
@endphp

<div class="page-frame">

    {{-- ══ HEADER ══ --}}
    <div class="hdr">
        <table>
            <tr>
                <td class="hdr-logo-cell">
                    @if($pubLogoPath && file_exists($pubLogoPath))
                        <img src="{{ $pubLogoPath }}" class="hdr-logo" alt="">
                    @else
                        <div class="logo-circle">LOGO</div>
                    @endif
                </td>
                <td class="hdr-center">
                    <div class="hdr-pub-name">{{ strtoupper($pubName ?: 'PUBLISHER') }}</div>
                    <div class="hdr-journal-name">{{ $jName }}</div>
                    @if($issnStr)
                        <div class="hdr-issn">{{ $issnStr }}</div>
                    @endif
                    @if($pubEmail || $pubPhone)
                        <div class="hdr-contact">
                            @if($pubEmail) {{ $pubEmail }} @endif
                            @if($pubEmail && $pubPhone) &nbsp;|&nbsp; @endif
                            @if($pubPhone) {{ $pubPhone }} @endif
                        </div>
                    @endif
                </td>
                <td class="hdr-logo-cell">
                    @if($jrnLogoPath && file_exists($jrnLogoPath))
                        <img src="{{ $jrnLogoPath }}" class="hdr-logo" alt="">
                    @else
                        <div class="logo-circle">JURNAL</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ══ TITLE BAND ══ --}}
    <div class="title-band">
        <div class="doc-title">{{ $certTitle }}</div>
        <div class="doc-sub">{{ $certSub }}</div>
        <div><span class="loa-badge">No: {{ $loaCode }}</span></div>
    </div>

    {{-- ══ BODY ══ --}}
    <div class="body-pad">

        {{-- Article Info --}}
        <table class="info-tbl">
            <tr>
                <td class="info-lbl">{{ $isId ? 'Judul Artikel' : 'Article Title' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val article-val">{{ $artTitle }}</td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'Penulis' : 'Author(s)' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $author }}</td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'Email Penulis' : 'Author Email' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $email }}</td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'Nama Jurnal' : 'Journal Name' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val"><strong>{{ $jName }}</strong></td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'Volume / Nomor' : 'Volume / Number' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val">Vol.&nbsp;{{ $vol }}&nbsp;&nbsp;/&nbsp;&nbsp;No.&nbsp;{{ $num }}</td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'Edisi Terbit' : 'Issue' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $month }} {{ $year }}</td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'No. Registrasi' : 'Registration No.' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val"><span class="reg-val">{{ $noReg }}</span></td>
            </tr>
            <tr>
                <td class="info-lbl">{{ $isId ? 'Tanggal Disetujui' : 'Approval Date' }}</td>
                <td class="info-sep">:</td>
                <td class="info-val date-val">{{ $approvalDate }}</td>
            </tr>
        </table>

        {{-- Accepted Banner --}}
        <div class="accepted-banner">
            <div class="accepted-main">
                &#10003;&nbsp;{{ $isId ? 'DITERIMA UNTUK DIPUBLIKASIKAN' : 'ACCEPTED FOR PUBLICATION' }}
            </div>
            <div class="accepted-en">
                {{ $isId ? 'Has Been Accepted for Publication' : 'Telah Diterima untuk Dipublikasikan' }}
            </div>
        </div>

        {{-- Statement --}}
        <div class="statement-box">
            @if($isId)
                Dengan ini dinyatakan bahwa naskah artikel ilmiah di atas telah melalui proses <strong>review</strong> oleh Mitra Bebestari dan diputuskan <strong>DITERIMA</strong> untuk dipublikasikan pada jurnal <strong>{{ $jName }}</strong> edisi Volume {{ $vol }}, Nomor {{ $num }}, {{ $month }} {{ $year }}. Surat persetujuan ini merupakan bukti resmi penerimaan naskah yang sah untuk keperluan akademik dan profesional.
            @else
                This is to certify that the manuscript above has completed the <strong>peer-review</strong> process and has been officially <strong>ACCEPTED</strong> for publication in <strong>{{ $jName }}</strong>, Volume {{ $vol }}, Number {{ $num }}, {{ $month }} {{ $year }}. This letter constitutes official proof of acceptance for academic and professional purposes.
            @endif
        </div>

        <div class="divider"></div>

        {{-- Footer: QR | Signature | Verification --}}
        <table class="footer-tbl">
            <tr>
                {{-- QR CODE --}}
                <td class="qr-cell">
                    @if(isset($qrCode) && $qrCode)
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" class="qr-img" alt="QR">
                    @else
                        <div class="qr-placeholder">QR<br>CODE</div>
                    @endif
                    <div class="qr-label">{{ $isId ? 'Scan untuk verifikasi' : 'Scan to verify' }}</div>
                </td>

                {{-- SIGNATURE --}}
                <td class="sig-cell">
                    <div class="sig-place">{{ $approvalDate }}</div>
                    <div class="sig-role">{{ $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>

                    <div class="sig-img-wrap">
                        @if($stampPath && file_exists($stampPath))
                            <img src="{{ $stampPath }}" class="sig-img" alt="TTD">
                        @else
                            <div class="sig-line"></div>
                        @endif
                    </div>

                    <div class="sig-name">{{ $chiefEd }}</div>
                    <div class="sig-title">{{ $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>
                    <div class="sig-journal">{{ $jName }}</div>
                </td>

                {{-- VERIFICATION --}}
                <td class="verify-cell">
                    <div class="verify-title">
                        &#128274;&nbsp;{{ $isId ? 'Verifikasi Dokumen' : 'Document Verification' }}
                    </div>
                    <div class="verify-text">
                        {{ $isId ? 'Verifikasi keaslian dokumen di:' : 'Verify document authenticity at:' }}<br>
                        <span class="verify-url">{{ $verifyUrl }}</span><br><br>
                        {{ $isId ? 'Kode LOA' : 'LOA Code' }}:<br>
                        <strong>{{ $loaCode }}</strong><br><br>
                        {{ $isId ? 'Dicetak pada' : 'Generated on' }}:<br>
                        {{ $nowStr }}
                    </div>
                    <div>
                        <span class="validated-chip">&#10003;&nbsp;{{ $isId ? 'TERVALIDASI' : 'VALIDATED' }}</span>
                    </div>
                </td>
            </tr>
        </table>

    </div>{{-- /body-pad --}}

    {{-- ══ BOTTOM BAR ══ --}}
    <div class="bottom-bar">
        {{ $isId ? 'Dokumen dibuat otomatis oleh' : 'Document generated by' }}
        <strong style="color:#c9a84c;">LOA Management System</strong>
        &nbsp;&#183;&nbsp; {{ $loaCode }}
        &nbsp;&#183;&nbsp; {{ $verifyUrl }}
    </div>

</div>{{-- /page-frame --}}

</body>
</html>
