<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
<meta charset="UTF-8">
<title>{{ $certTitle }} – {{ $loaCode }}</title>
<style>
@page { margin: 12mm 14mm 12mm 14mm; size: A4 portrait; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9pt; color: #1a1a2e; background: #fff; }

/* ── FRAME ── */
.frame { border: 2pt solid #1e3a6e; width: 100%; }

/* ── HEADER ── */
.hdr { background-color: #1e3a6e; padding: 10pt 12pt; }
.hdr table { width: 100%; border-collapse: collapse; }
.hdr td { padding: 0; vertical-align: middle; }
.hdr-logo { width: 54pt; text-align: center; }
.hdr-logo img { max-width: 48pt; max-height: 48pt; }
.logo-ph { width: 44pt; height: 44pt; border: 1pt solid rgba(255,255,255,0.3); border-radius: 22pt; margin: 0 auto; padding-top: 14pt; text-align: center; font-size: 6pt; color: rgba(255,255,255,0.5); }
.hdr-mid { text-align: center; padding: 0 8pt; }
.hdr-pub { font-size: 13pt; font-weight: bold; color: #ffffff; margin-bottom: 2pt; }
.hdr-jrn { font-size: 9pt; color: rgba(255,255,255,0.85); margin-bottom: 2pt; }
.hdr-issn { font-size: 7.5pt; color: rgba(255,255,255,0.65); margin-bottom: 1pt; }
.hdr-ct { font-size: 7pt; color: rgba(255,255,255,0.55); }

/* ── GOLD LINE ── */
.gold-line { height: 3pt; background-color: #c9a84c; }

/* ── TITLE BAND ── */
.title-band { background-color: #f4f7fb; border-bottom: 1pt solid #d0d8e8; padding: 8pt 12pt; text-align: center; }
.doc-title { font-size: 12pt; font-weight: bold; color: #1e3a6e; letter-spacing: 0.5pt; }
.doc-sub { font-size: 8pt; color: #6c757d; margin-top: 2pt; }
.loa-badge { font-size: 8.5pt; font-weight: bold; color: #1e3a6e; margin-top: 4pt; border: 1pt solid #1e3a6e; padding: 2pt 10pt; border-radius: 12pt; display: inline-block; }

/* ── BODY ── */
.body { padding: 9pt 12pt 6pt 12pt; }

.to-label { font-size: 8pt; color: #6c757d; margin-bottom: 1pt; }
.author-name { font-size: 12pt; font-weight: bold; color: #1a1a2e; margin-bottom: 1pt; }
.author-email { font-size: 8pt; color: #888; margin-bottom: 8pt; }

.art-box { background-color: #eef2fb; border-left: 4pt solid #1e3a6e; padding: 7pt 10pt; margin-bottom: 7pt; }
.art-lbl { font-size: 7.5pt; color: #6c757d; font-weight: bold; margin-bottom: 2pt; text-transform: uppercase; }
.art-title { font-size: 9.5pt; font-weight: bold; color: #1e3a6e; line-height: 1.45; }

.accepted-box { background-color: #e9f7ef; border-left: 4pt solid #28a745; padding: 7pt 10pt; margin-bottom: 7pt; }
.accepted-main { font-size: 10pt; font-weight: bold; color: #155724; }
.accepted-sub { font-size: 7.5pt; color: #388e3c; margin-top: 1pt; }

.stmt { font-size: 8.5pt; color: #374151; text-align: justify; line-height: 1.55; margin-bottom: 8pt; padding: 6pt 8pt; background-color: #fafafa; border-left: 3pt solid #c9a84c; }

/* ── INFO TABLE ── */
.info-tbl { width: 100%; border-collapse: collapse; margin-bottom: 8pt; }
.info-tbl td { padding: 3.5pt 6pt; border-bottom: 0.5pt solid #e5e8ee; font-size: 8.5pt; vertical-align: top; }
.info-lbl { color: #5a6472; font-weight: bold; width: 34%; white-space: nowrap; }
.info-sep { width: 8pt; color: #aaa; }
.info-val { color: #1a1a2e; }
.val-jrn { font-weight: bold; color: #1e3a6e; }
.val-date { font-weight: bold; color: #1a6630; }
.val-reg { background-color: #eef2f7; padding: 0pt 4pt; font-size: 8pt; }

/* ── SIGNATURE ── */
.sig-outer { width: 100%; }
.sig-outer td { vertical-align: bottom; padding: 0; }
.sig-right { width: 200pt; text-align: center; }
.sig-date { font-size: 8pt; color: #495057; margin-bottom: 2pt; }
.sig-role { font-size: 8pt; font-weight: bold; color: #1e3a6e; margin-bottom: 6pt; }
.sig-img-wrap { height: 62pt; text-align: center; }
.sig-img { max-height: 58pt; max-width: 130pt; }
.sig-blank { height: 58pt; border-bottom: 1.2pt solid #333; margin: 0 20pt; }
.sig-name { font-size: 9pt; font-weight: bold; color: #1a1a2e; border-top: 1.2pt solid #1a1a2e; padding-top: 4pt; display: inline-block; min-width: 160pt; margin-top: 4pt; }
.sig-title { font-size: 7.5pt; color: #6c757d; margin-top: 2pt; }
.sig-jrn { font-size: 7pt; color: #888; }

/* ── FOOTER ── */
.footer { background-color: #1e3a6e; padding: 10pt 12pt; }
.footer table { width: 100%; border-collapse: collapse; }
.footer td { padding: 0; vertical-align: middle; }
.qr-cell { width: 72pt; text-align: center; }
.qr-img { width: 68pt; height: 68pt; background-color: #fff; padding: 2pt; }
.qr-ph { width: 68pt; height: 68pt; border: 1pt dashed rgba(255,255,255,0.4); padding-top: 26pt; text-align: center; font-size: 6pt; color: rgba(255,255,255,0.5); }
.qr-lbl { font-size: 6.5pt; color: rgba(255,255,255,0.6); text-align: center; margin-top: 2pt; }
.verify-cell { padding-left: 12pt; }
.verify-title { font-size: 9pt; font-weight: bold; color: #c9a84c; margin-bottom: 3pt; }
.verify-url { font-size: 7.5pt; color: rgba(255,255,255,0.75); margin-bottom: 2pt; word-break: break-all; }
.verify-note { font-size: 7pt; color: rgba(255,255,255,0.5); margin-top: 3pt; }
.valid-cell { width: 88pt; text-align: center; }
.valid-badge { display: inline-block; background-color: #28a745; color: #fff; font-size: 8pt; font-weight: bold; padding: 4pt 8pt; border-radius: 10pt; }
</style>
</head>
<body>

<table class="frame">
<tr><td>

{{-- ── HEADER ── --}}
<div class="hdr">
    <table>
        <tr>
            <td class="hdr-logo">
                @if($pubLogoPath)
                    <img src="{{ $pubLogoPath }}" alt="">
                @else
                    <div class="logo-ph">LOGO</div>
                @endif
            </td>
            <td class="hdr-mid">
                <div class="hdr-pub">{{ strtoupper($pubName ?: 'PUBLISHER') }}</div>
                <div class="hdr-jrn">{{ $journalName }}</div>
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
            </td>
            <td class="hdr-logo">
                @if($jrnLogoPath)
                    <img src="{{ $jrnLogoPath }}" alt="">
                @else
                    <div class="logo-ph">JURNAL</div>
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- ── GOLD LINE ── --}}
<div class="gold-line"></div>

{{-- ── TITLE BAND ── --}}
<div class="title-band">
    <div class="doc-title">{{ $certTitle }}</div>
    <div class="doc-sub">{{ $certSub }}</div>
    <div class="loa-badge">No: {{ $loaCode }}</div>
</div>

{{-- ── BODY ── --}}
<div class="body">

    <div class="to-label">{{ $isId ? 'Kepada Yth.' : 'To' }}:</div>
    <div class="author-name">{{ $author }}</div>
    <div class="author-email">{{ $email }}</div>

    <div class="art-box">
        <div class="art-lbl">{{ $isId ? 'Judul Artikel' : 'Article Title' }}</div>
        <div class="art-title">"{{ $artTitle }}"</div>
    </div>

    <div class="accepted-box">
        <div class="accepted-main">
            &#10003;&nbsp;{{ $isId ? 'DITERIMA UNTUK DIPUBLIKASIKAN' : 'ACCEPTED FOR PUBLICATION' }}
        </div>
        <div class="accepted-sub">
            {{ $isId ? 'Has Been Accepted for Publication' : 'Telah Diterima untuk Dipublikasikan' }}
        </div>
    </div>

    <div class="stmt">
        @if($isId)
            Dengan ini dinyatakan bahwa naskah artikel ilmiah di atas telah melalui proses <strong>review</strong>
            oleh Mitra Bebestari dan diputuskan <strong>DITERIMA</strong> untuk dipublikasikan pada jurnal
            <strong>{{ $journalName }}</strong> edisi Volume {{ $volume }}, Nomor {{ $number }},
            {{ $month }} {{ $year }}.
            Surat persetujuan ini merupakan bukti resmi penerimaan naskah yang sah.
        @else
            This is to certify that the manuscript above has completed the <strong>peer-review</strong> process
            and has been officially <strong>ACCEPTED</strong> for publication in
            <strong>{{ $journalName }}</strong>, Volume {{ $volume }}, Number {{ $number }},
            {{ $month }} {{ $year }}.
            This letter constitutes official proof of acceptance for academic and professional purposes.
        @endif
    </div>

    {{-- INFO TABLE --}}
    <table class="info-tbl">
        <tr>
            <td class="info-lbl">{{ $isId ? 'Nama Jurnal' : 'Journal Name' }}</td>
            <td class="info-sep">:</td>
            <td class="info-val val-jrn">{{ $journalName }}</td>
        </tr>
        <tr>
            <td class="info-lbl">{{ $isId ? 'Volume / Nomor' : 'Volume / Number' }}</td>
            <td class="info-sep">:</td>
            <td class="info-val">Vol. {{ $volume }} &nbsp;/&nbsp; No. {{ $number }}</td>
        </tr>
        <tr>
            <td class="info-lbl">{{ $isId ? 'Edisi Terbit' : 'Issue' }}</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $month }} {{ $year }}</td>
        </tr>
        <tr>
            <td class="info-lbl">{{ $isId ? 'No. Registrasi' : 'Registration No.' }}</td>
            <td class="info-sep">:</td>
            <td class="info-val"><span class="val-reg">{{ $noReg }}</span></td>
        </tr>
        <tr>
            <td class="info-lbl">{{ $isId ? 'Tanggal Disetujui' : 'Approval Date' }}</td>
            <td class="info-sep">:</td>
            <td class="info-val val-date">{{ $approvalDate }}</td>
        </tr>
    </table>

    {{-- SIGNATURE --}}
    <table class="sig-outer">
        <tr>
            <td>&nbsp;</td>
            <td class="sig-right">
                <div class="sig-date">{{ $approvalDate }}</div>
                <div class="sig-role">{{ $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>
                <div class="sig-img-wrap">
                    @if($stampPath)
                        <img src="{{ $stampPath }}" class="sig-img" alt="">
                    @else
                        <div class="sig-blank"></div>
                    @endif
                </div>
                <div><span class="sig-name">{{ $chiefEditor }}</span></div>
                <div class="sig-title">{{ $isId ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>
                <div class="sig-jrn">{{ $journalName }}</div>
            </td>
        </tr>
    </table>

</div>{{-- /body --}}

{{-- ── FOOTER ── --}}
<div class="footer">
    <table>
        <tr>
            <td class="qr-cell">
                @if($qrCode)
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" class="qr-img" alt="QR">
                @else
                    <div class="qr-ph">QR CODE</div>
                @endif
                <div class="qr-lbl">{{ $isId ? 'Scan untuk verifikasi' : 'Scan to verify' }}</div>
            </td>
            <td class="verify-cell">
                <div class="verify-title">&#128274; {{ $isId ? 'Verifikasi Dokumen' : 'Document Verification' }}</div>
                <div class="verify-url">{{ $verifyUrl }}</div>
                <div class="verify-url">{{ $isId ? 'Kode LOA' : 'LOA Code' }}: <strong style="color:#fff;">{{ $loaCode }}</strong></div>
                <div class="verify-note">{{ $isId ? 'Dicetak pada' : 'Generated on' }}: {{ $nowStr }}</div>
            </td>
            <td class="valid-cell">
                <div class="valid-badge">&#10003; {{ $isId ? 'TERVALIDASI' : 'VALIDATED' }}</div>
            </td>
        </tr>
    </table>
</div>

</td></tr>
</table>

</body>
</html>
