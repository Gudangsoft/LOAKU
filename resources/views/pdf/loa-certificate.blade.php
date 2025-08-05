<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'id' ? 'Surat Persetujuan Naskah' : 'Letter of Acceptance' }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c5aa0;
        }
        
        .header h1 {
            color: #2c5aa0;
            font-size: 16px;
            margin: 5px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #666;
        }
        
        .header h2 {
            color: #2c5aa0;
            font-size: 14px;
            margin: 15px 0 5px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .loa-number {
            text-align: center;
            background: #f8f9fa;
            padding: 8px;
            margin: 15px 0;
            border: 1px solid #2c5aa0;
            border-radius: 3px;
        }
        
        .loa-number strong {
            font-size: 12px;
            color: #2c5aa0;
        }
        
        .content {
            margin: 20px 0;
            text-align: justify;
        }
        
        .article-info {
            background: #f8f9fa;
            padding: 12px;
            margin: 15px 0;
            border-left: 4px solid #2c5aa0;
        }
        
        .article-info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .article-info td {
            padding: 4px 8px;
            vertical-align: top;
            border-bottom: 1px dotted #ddd;
        }
        
        .article-info td:first-child {
            width: 25%;
            font-weight: bold;
            color: #333;
        }
        
        .acceptance-text {
            text-align: center;
            font-weight: bold;
            color: #2c5aa0;
            font-size: 12px;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #2c5aa0;
            background: #f0f8ff;
        }
        
        .signature-area {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature-box {
            display: inline-block;
            text-align: center;
            margin-top: 15px;
        }
        
        .signature-date {
            margin-bottom: 10px;
            font-size: 10px;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 50px;
        }
        
        .signature-name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 45px;
        }
        
        .verification-info {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $publisher->name ?? 'Publisher Name' }}</h1>
        <p>{{ $publisher->address ?? 'Publisher Address' }}</p>
        <p>Email: {{ $publisher->email ?? 'email@publisher.com' }} | Phone: {{ $publisher->phone ?? '+62-xxx-xxxx' }}</p>
        
        <h2>
            @if($lang == 'id')
                SURAT PERSETUJUAN NASKAH<br>
                (LETTER OF ACCEPTANCE)
            @else
                LETTER OF ACCEPTANCE<br>
                (SURAT PERSETUJUAN NASKAH)
            @endif
        </h2>
    </div>

    <!-- LOA Number -->
    <div class="loa-number">
        <strong>{{ $lang == 'id' ? 'Nomor LOA' : 'LOA Number' }}: {{ $loa->loa_code ?? 'LOA-CODE' }}</strong>
    </div>

    <!-- Content -->
    <div class="content">
        @if($lang == 'id')
            <p>Dengan ini kami menyatakan bahwa naskah artikel ilmiah berikut:</p>
        @else
            <p>We hereby certify that the following scientific article manuscript:</p>
        @endif
        
        <div class="article-info">
            <table>
                <tr>
                    <td>{{ $lang == 'id' ? 'Judul' : 'Title' }}:</td>
                    <td><strong>{{ $request->article_title ?? 'Article Title' }}</strong></td>
                </tr>
                <tr>
                    <td>{{ $lang == 'id' ? 'Penulis' : 'Author(s)' }}:</td>
                    <td>{{ $request->author ?? 'Author Name' }}</td>
                </tr>
                <tr>
                    <td>{{ $lang == 'id' ? 'Email' : 'Email' }}:</td>
                    <td>{{ $request->author_email ?? 'author@email.com' }}</td>
                </tr>
                <tr>
                    <td>{{ $lang == 'id' ? 'Jurnal' : 'Journal' }}:</td>
                    <td>{{ $journal->name ?? 'Journal Name' }}</td>
                </tr>
                <tr>
                    <td>ISSN:</td>
                    <td>
                        @if(isset($journal->e_issn)) E-ISSN: {{ $journal->e_issn }} @endif
                        @if(isset($journal->p_issn)) | P-ISSN: {{ $journal->p_issn }} @endif
                    </td>
                </tr>
                <tr>
                    <td>{{ $lang == 'id' ? 'Volume/Nomor' : 'Volume/Number' }}:</td>
                    <td>Vol. {{ $request->volume ?? 'X' }}, No. {{ $request->number ?? 'X' }}</td>
                </tr>
                <tr>
                    <td>{{ $lang == 'id' ? 'Edisi' : 'Issue' }}:</td>
                    <td>{{ $request->month ?? 'Month' }} {{ $request->year ?? 'Year' }}</td>
                </tr>
                <tr>
                    <td>{{ $lang == 'id' ? 'No. Registrasi' : 'Registration No.' }}:</td>
                    <td>{{ $request->no_reg ?? 'REG-XXX' }}</td>
                </tr>
            </table>
        </div>

        <div class="acceptance-text">
            @if($lang == 'id')
                TELAH DITERIMA UNTUK DIPUBLIKASIKAN<br>
                (HAS BEEN ACCEPTED FOR PUBLICATION)
            @else
                HAS BEEN ACCEPTED FOR PUBLICATION<br>
                (TELAH DITERIMA UNTUK DIPUBLIKASIKAN)
            @endif
        </div>

        @if($lang == 'id')
            <p>Naskah telah melalui proses review dan telah memenuhi persyaratan untuk dipublikasikan pada jurnal <strong>{{ $journal->name ?? 'Journal Name' }}</strong>. Surat persetujuan ini merupakan bukti resmi penerimaan naskah untuk publikasi.</p>
        @else
            <p>The manuscript has undergone the review process and has met the requirements for publication in <strong>{{ $journal->name ?? 'Journal Name' }}</strong>. This letter of acceptance serves as official proof of manuscript acceptance for publication.</p>
        @endif
    </div>

    <!-- Signature Area -->
    <div class="signature-area">
        <div class="signature-date">
            {{ isset($request->approved_at) ? $request->approved_at->format('d F Y') : date('d F Y') }}
        </div>
        
        <div class="signature-box">
            <div class="signature-title">{{ $lang == 'id' ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</div>
            <div class="signature-name">{{ $journal->chief_editor ?? 'Chief Editor Name' }}</div>
        </div>
    </div>

    <!-- Verification Info -->
    <div class="verification-info">
        <strong>{{ $lang == 'id' ? 'Verifikasi Dokumen' : 'Document Verification' }}:</strong>
        {{ $lang == 'id' ? 'Kunjungi' : 'Visit' }} {{ url('/verify-loa') }} 
        {{ $lang == 'id' ? 'dengan kode' : 'with code' }}: {{ $loa->loa_code ?? 'LOA-CODE' }}
        <br>
        {{ $lang == 'id' ? 'Diterbitkan pada' : 'Generated on' }}: {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>
            color: #999;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .verification-url {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #0066cc;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($publisher->logo)
            <img src="{{ public_path('storage/' . $publisher->logo) }}" alt="Logo" style="height: 60px; margin-bottom: 10px;">
        @endif
        
        <h1>{{ $publisher->name }}</h1>
        <p>{{ $publisher->address }}</p>
        <p>{{ $lang == 'id' ? 'Email' : 'Email' }}: {{ $publisher->email }} | 
           {{ $lang == 'id' ? 'Telepon' : 'Phone' }}: {{ $publisher->phone }}</p>
        
        <h2 style="margin-top: 30px;">
            @if($lang == 'id')
                SURAT PERSETUJUAN NASKAH<br>
                (LETTER OF ACCEPTANCE)
            @else
                LETTER OF ACCEPTANCE<br>
                (SURAT PERSETUJUAN NASKAH)
            @endif
        </h2>
    </div>
    
    <!-- LOA Code -->
    <div class="loa-code">
        <strong>{{ $lang == 'id' ? 'Kode LOA' : 'LOA Code' }}: {{ $loa->loa_code }}</strong>
    </div>
    
    <!-- Content -->
    <div class="content">
        @if($lang == 'id')
            <p>Dengan ini kami menyatakan bahwa naskah artikel ilmiah yang berjudul:</p>
        @else
            <p>We hereby declare that the scientific article manuscript entitled:</p>
        @endif
        
        <table class="details-table">
            <tr>
                <td>{{ $lang == 'id' ? 'Judul Artikel' : 'Article Title' }}:</td>
                <td><strong>{{ $request->article_title }}</strong></td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'Penulis' : 'Author' }}:</td>
                <td>{{ $request->author }}</td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'Email Penulis' : 'Author Email' }}:</td>
                <td>{{ $request->author_email }}</td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'Jurnal' : 'Journal' }}:</td>
                <td>{{ $journal->name }}</td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'ISSN' : 'ISSN' }}:</td>
                <td>
                    @if($journal->e_issn) E-ISSN: {{ $journal->e_issn }} @endif
                    @if($journal->p_issn) | P-ISSN: {{ $journal->p_issn }} @endif
                </td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'Volume & Nomor' : 'Volume & Number' }}:</td>
                <td>{{ $lang == 'id' ? 'Volume' : 'Volume' }} {{ $request->volume }}, {{ $lang == 'id' ? 'Nomor' : 'Number' }} {{ $request->number }}</td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'Edisi' : 'Issue' }}:</td>
                <td>{{ $request->month }} {{ $request->year }}</td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'No. Registrasi' : 'Registration No.' }}:</td>
                <td>{{ $request->no_reg }}</td>
            </tr>
            <tr>
                <td>{{ $lang == 'id' ? 'Tanggal Persetujuan' : 'Approval Date' }}:</td>
                <td>{{ $request->approved_at->format('d F Y') }}</td>
            </tr>
        </table>
        
        @if($lang == 'id')
            <p><strong>TELAH DITERIMA</strong> untuk dipublikasikan pada jurnal <strong>{{ $journal->name }}</strong> setelah melalui proses review dan memenuhi persyaratan editorial yang ditetapkan.</p>
            
            <p>Surat persetujuan ini merupakan bukti resmi bahwa naskah artikel telah disetujui untuk dipublikasikan dan dapat digunakan untuk keperluan akademik dan profesional.</p>
        @else
            <p><strong>HAS BEEN ACCEPTED</strong> for publication in <strong>{{ $journal->name }}</strong> after going through the review process and meeting the established editorial requirements.</p>
            
            <p>This letter of acceptance is official proof that the article manuscript has been approved for publication and can be used for academic and professional purposes.</p>
        @endif
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <p>{{ $request->approved_at->format('d F Y') }}</p>
        <p><strong>{{ $lang == 'id' ? 'Editor-in-Chief' : 'Editor-in-Chief' }}</strong></p>
        
        @if($journal->ttd_stample)
            <div style="margin: 20px 0;">
                <img src="{{ public_path('storage/' . $journal->ttd_stample) }}" alt="Signature & Stamp" style="height: 80px;">
            </div>
        @else
            <div class="stamp-area">
                {{ $lang == 'id' ? 'Tanda Tangan & Stempel' : 'Signature & Stamp' }}
            </div>
        @endif
        
        <div class="signature-box">
            <strong>{{ $journal->chief_editor }}</strong><br>
            <small>{{ $lang == 'id' ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</small>
        </div>
    </div>
    
    <!-- Verification URL -->
    <div class="verification-url">
        <strong>{{ $lang == 'id' ? 'Verifikasi Dokumen' : 'Document Verification' }}:</strong><br>
        {{ $lang == 'id' ? 'Untuk memverifikasi keaslian dokumen ini, kunjungi' : 'To verify the authenticity of this document, visit' }}: 
        {{ url('/verify-loa') }}<br>
        {{ $lang == 'id' ? 'Masukkan kode LOA' : 'Enter LOA code' }}: <strong>{{ $loa->loa_code }}</strong>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>{{ $lang == 'id' ? 'Dokumen ini dibuat secara otomatis oleh Sistem Manajemen LOA SIPTENAN' : 'This document is automatically generated by SIPTENAN LOA Management System' }}</p>
        <p>{{ $lang == 'id' ? 'Dicetak pada' : 'Printed on' }}: {{ now()->format('d F Y, H:i:s') }}</p>
    </div>
</body>
</html>
