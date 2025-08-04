<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'id' ? 'Surat Keterangan Publikasi Artikel Jurnal' : 'Letter of Acceptance (LoA)' }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4 portrait;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .header {
            width: 100%;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #003366;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-left {
            width: 20%;
            text-align: center;
            vertical-align: middle;
        }
        
        .header-center {
            width: 60%;
            text-align: center;
            vertical-align: middle;
        }
        
        .header-right {
            width: 20%;
            text-align: center;
            vertical-align: middle;
        }
        
        .company-logo, .publisher-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .logo-placeholder {
            width: 60px;
            height: 60px;
            border: 1px solid #ccc;
            display: table;
            margin: 0 auto;
            background-color: #f9f9f9;
        }
        
        .logo-placeholder-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .header-center h1 {
            color: #003366;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header-center .subtitle {
            font-size: 11px;
            margin: 2px 0;
            color: #666;
        }
        
        .document-title {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        
        .document-title h2 {
            color: #003366;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
            text-decoration: underline;
        }
        
        .loa-number {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            font-size: 11px;
        }
        
        .content {
            margin: 20px 0;
            text-align: justify;
        }
        
        .recipient {
            margin: 15px 0;
        }
        
        .article-info {
            margin: 20px 0;
            font-size: 11px;
        }
        
        .article-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
        }
        
        .acceptance-statement {
            margin: 20px 0;
            text-align: justify;
        }
        
        .footer-section {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .qr-section {
            width: 40%;
            vertical-align: top;
            text-align: left;
            padding: 10px;
        }
        
        .signature-section {
            width: 60%;
            vertical-align: top;
            text-align: center;
            padding: 10px;
        }
        
        .verification-info {
            margin-top: 10px;
            font-size: 9px;
            line-height: 1.3;
        }
        
        .signature-section {
            text-align: center;
            flex: 1;
        }
        
        .signature-date {
            margin-bottom: 15px;
        }
        
        .signature-title {
            font-weight: bold;
            margin: 10px 0;
        }
        
        .signature-space {
            height: 60px;
            margin: 10px 0;
        }
        
        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            text-align: center;
            padding-bottom: 2px;
        }
        
        .editor-title {
            font-size: 11px;
            margin-top: 5px;
        }
        
        .publisher-info {
            margin-top: 30px;
            font-size: 10px;
            text-align: left;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .verification-info {
            font-size: 10px;
            margin-top: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        table td {
            padding: 3px 5px;
            vertical-align: top;
        }
        
        .reg-number {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <!-- Header with Logos -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <!-- Company/Publisher Logo -->
                    @if(isset($publisher) && isset($publisher->logo) && $publisher->logo && file_exists(public_path('storage/' . $publisher->logo)))
                        <img src="{{ public_path('storage/' . $publisher->logo) }}" alt="Company Logo" class="company-logo">
                    @else
                        <div class="logo-placeholder">
                            <div class="logo-placeholder-text">
                                COMPANY<br>LOGO
                            </div>
                        </div>
                    @endif
                </td>
                
                <td class="header-center">
                    <h1>{{ strtoupper((isset($publisher) ? $publisher->name : null) ?? 'SIPTENAN') }}</h1>
                    <div class="subtitle">
                        {{ $lang == 'id' 
                            ? ((isset($journal) ? $journal->name : null) ?? 'Jurnal SIPTENAN') 
                            : ('Writing: ' . ((isset($journal) ? $journal->name : null) ?? 'Siptenan Research Journal')) 
                        }}
                    </div>
                    <div class="subtitle">HP: {{ (isset($publisher) ? $publisher->phone : null) ?? '+62 851-5862-9831' }} | E-Mail: {{ (isset($publisher) ? $publisher->email : null) ?? 'padang.tekno.corp@gmail.com' }}</div>
                    <div class="subtitle">E-ISSN: {{ (isset($journal) ? $journal->e_issn : null) ?? '3018-683X' }} | P-ISSN: {{ (isset($journal) ? $journal->p_issn : null) ?? '3018-683X' }}</div>
                </td>
                
                <td class="header-right">
                    <!-- Journal Logo -->
                    @if(isset($journal) && isset($journal->logo) && $journal->logo && file_exists(public_path('storage/' . $journal->logo)))
                        <img src="{{ public_path('storage/' . $journal->logo) }}" alt="Journal Logo" class="publisher-logo">
                    @else
                        <div class="logo-placeholder">
                            <div class="logo-placeholder-text">
                                JOURNAL<br>LOGO
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Document Title -->
    <div class="document-title">
        <h2>
            @if($lang == 'id')
                SURAT KETERANGAN PUBLIKASI ARTIKEL JURNAL<br>
                <em>Letter Of Acceptance (LoA)</em>
            @else
                LETTER OF ACCEPTANCE (LOA)<br>
                <em>Surat Keterangan Publikasi Artikel Jurnal</em>
            @endif
        </h2>
    </div>
    
    <!-- LOA Number -->
    <div class="loa-number">
        {{ $lang == 'id' ? 'Nomor' : 'Number' }}: {{ (isset($loa) ? $loa->loa_code : null) ?? '711/Menulis-Vol.1/No.8/2025' }}
    </div>
    
    <!-- Recipient -->
    <div class="recipient">
        <strong>{{ $lang == 'id' ? 'Kepada Yth.' : 'To' }}:</strong><br>
        {{ (isset($request) ? $request->author : null) ?? 'Andraal Celvin, Ruth Deby Sarvalistia, Khairunisa' }}
    </div>
    
    <!-- Content -->
    <div class="content">
        @if($lang == 'id')
            <p><strong>Terima kasih</strong> Telah Mengirimkan Artikel Terbaik anda untuk diterbitkan Pada <strong>{{ (isset($journal) ? $journal->name : null) ?? 'Menulis: Jurnal Penelitian Siptenan' }}</strong> dengan judul:</p>
        @else
            <p><strong>Thank you</strong> for submitting your best article for publication in <strong>{{ (isset($journal) ? $journal->name : null) ?? 'Menulis: Jurnal Penelitian Nusantara' }}</strong> with the title:</p>
        @endif
    </div>
    
    <!-- Article Title -->
    <div class="article-title">
        "{{ (isset($request) ? $request->article_title : null) ?? 'Perancangan Media Informasi Desa Wisata Partungko Naginjang Kabupaten Samosir Melalui Media Website' }}"
    </div>
    
    <!-- Acceptance Statement -->
    <div class="acceptance-statement">
        @if($lang == 'id')
            <p>Berdasarkan Hasil  Review dan Keputusan Tim Editor, Maka Artikel  Tersebut dinyatakan <strong>DITERIMA</strong> untuk dipublikasikan pada <strong>{{ (isset($journal) ? $journal->name : null) ?? 'Menulis: Jurnal Penelitian Siptenan' }}</strong> Edisi <strong>Volume {{ (isset($request) ? $request->volume : null) ?? '1' }} Nomor {{ (isset($request) ? $request->number : null) ?? '8' }}</strong> bulan <strong>{{ (isset($request) ? $request->month : null) ?? 'Agustus' }} {{ (isset($request) ? $request->year : null) ?? '2025' }}</strong>.</p>

            <p>Demikian surat keterangan ini kami sampaikan untuk dipergunakan sebagaimana mestinya, kami ucapkan terimakasih.</p>
        @else
            <p>Based on the Review results and editorial team decision, the article is declared <strong>ACCEPTED</strong> for publication in <strong>Menulis: Jurnal Penelitian Nusantara</strong> edition <strong>Volume {{ (isset($request) ? $request->volume : null) ?? '1' }} Number {{ (isset($request) ? $request->number : null) ?? '8' }}</strong> in <strong>{{ (isset($request) ? $request->month : null) ?? 'August' }} {{ (isset($request) ? $request->year : null) ?? '2025' }}</strong>.</p>
            
            <p>This letter of acceptance is issued for proper use as needed, thank you.</p>
        @endif
    </div>
    
    
<!-- Footer with QR Code and Signature -->
<div class="footer-section">
    <table class="footer-table">
        <tr>
            <td class="qr-section">
                <!-- QR Code -->
                <div style="text-align: center; margin-bottom: 10px;">
                    @if(isset($qrCodePath) && file_exists($qrCodePath))
                        <img src="{{ $qrCodePath }}" alt="QR Code" style="width: 80px; height: 80px; border: 1px solid #ddd;">
                    @elseif(isset($qrCode))
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 80px; height: 80px; border: 1px solid #ddd;">
                    @else
                        <div style="width: 80px; height: 80px; border: 2px solid #ddd; margin: 0 auto; display: table;">
                            <div style="display: table-cell; vertical-align: middle; text-align: center; font-size: 8px; color: #666;">
                                QR CODE<br>PLACEHOLDER
                            </div>
                        </div>
                    @endif
                </div>
                    
                    <div class="verification-info">
                        <strong>{{ $lang == 'id' ? 'Keaslian LOA Dapat' : 'LOA Authenticity Can' }}</strong><br>
                        <strong>{{ $lang == 'id' ? 'Diverifikasi Dengan' : 'Be Verified With' }}</strong><br>
                        <strong>{{ $lang == 'id' ? 'Memindai QR Code' : 'Scanning QR Code' }}</strong><br>
                        
                    </div>
                    
                    <div style="margin-top: 10px; font-size: 9px;">
                        {{ (isset($loa) ? $loa->loa_code : null) ?? 'LOA320580821323411' }}
                    </div>
                </td>
                
                <td class="signature-section">
                    <!-- Date -->
                    <div style="text-align: center; margin-bottom: 15px; font-size: 11px;">
                        {{ (isset($request) && isset($request->approved_at)) ? $request->approved_at->format('d F Y') : (date('d') . ' ' . 
                            ($lang == 'id' ? 
                                ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][date('n')-1] : 
                                date('F')
                            ) . ' ' . date('Y')
                        ) }}
                    </div>
                    
                    <!-- Title -->
                    <div style="text-align: center; font-weight: bold; margin: 10px 0; font-size: 11px;">
                        {{ $lang == 'id' ? 'Editor In Chief' : 'Editor In Chief' }}
                    </div>
                    
                    <!-- Signature Image or Space -->
                    <div style="height: 80px; margin: 15px 0; text-align: center;">
                        @if(isset($journal) && isset($journal->ttd_stample) && $journal->ttd_stample && file_exists(public_path('storage/' . $journal->ttd_stample)))
                            <!-- Real signature from database -->
                            <img src="{{ public_path('storage/' . $journal->ttd_stample) }}" alt="Signature & Stamp" style="max-height: 70px; max-width: 150px; object-fit: contain;">
                        @elseif(isset($journal) && isset($journal->signature_image) && $journal->signature_image && file_exists(public_path('storage/' . $journal->signature_image)))
                            <!-- Fallback to old signature field -->
                            <img src="{{ public_path('storage/' . $journal->signature_image) }}" alt="Signature" style="max-height: 70px; max-width: 150px; object-fit: contain;">
                        @else
                            <!-- Signature placeholder with stamp simulation -->
                            <div style="height: 70px; width: 150px; margin: 0 auto; position: relative; border: 1px dashed #ccc;">
                                <!-- Digital signature text -->
                                <div style="position: absolute; top: 25px; left: 20px; font-family: 'Times New Roman', serif; font-size: 12px; transform: rotate(-2deg); color: #003366; font-style: italic;">
                                    {{ (isset($journal) ? $journal->chief_editor : null) ?? 'Digital Signature' }}
                                </div>
                                <!-- Stamp placeholder -->
                                <div style="position: absolute; top: 10px; right: 15px; width: 40px; height: 40px; border: 2px solid #ff0000; border-radius: 50%; display: table;">
                                    <div style="display: table-cell; vertical-align: middle; text-align: center; font-size: 6px; color: #ff0000; font-weight: bold;">
                                        STAMP<br>HERE
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Chief Editor Name with underline -->
                    <div style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; display: inline-block; min-width: 150px; padding-bottom: 2px; font-size: 11px;">
                        {{ (isset($journal) ? $journal->chief_editor : null) ?? 'Mardalius, M.Kom' }}
                    </div>
                    
                    <!-- Editor Title -->
                    <div style="text-align: center; font-size: 10px; margin-top: 5px;">
                        {{ $lang == 'id' ? 'Editor-In-Chief' : 'Editor-In-Chief' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Publisher Info -->
    <div class="publisher-info">
        <strong>{{ $lang == 'id' ? 'Penerbit' : 'Publisher' }}:</strong><br>
        <strong>{{ (isset($publisher) ? $publisher->name : null) ?? 'PT. Padang Tekno Corp' }}</strong><br>
        Alamat : {{ (isset($publisher) ? $publisher->address : null) ?? 'Jl. Bandar Purus Nauli, Sumatera Utara.' }}<br>
       Phone :  {{ (isset($publisher) ? $publisher->phone : null) ?? '+62 851-5862-9831' }}<br>
       E- Mail :  {{ (isset($publisher) ? $publisher->email : null) ?? 'padang.tekno.corp@gmail.com' }}
    </div>
</body>
</html>
