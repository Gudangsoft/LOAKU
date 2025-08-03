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
        
        .company-logo {
            width: 60px;
            height: 60px;
        }
        
        .publisher-logo {
            width: 60px;
            height: 60px;
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
        }
        
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .qr-section {
            width: 40%;
            vertical-align: bottom;
            text-align: left;
        }
        
        .signature-section {
            width: 60%;
            vertical-align: bottom;
            text-align: center;
        }
        
        .qr-section {
            text-align: left;
            flex: 1;
            margin-right: 20px;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
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
                    @if(isset($publisher->logo) && $publisher->logo)
                        <img src="{{ public_path('storage/' . $publisher->logo) }}" alt="Company Logo" class="company-logo">
                    @else
                        <div class="company-logo" style="border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">LOGO</div>
                    @endif
                </td>
                
                <td class="header-center">
                    <h1>{{ strtoupper($publisher->name ?? 'PT. PADANG TEKNO CORP') }}</h1>
                    <div class="subtitle">{{ $lang == 'id' ? 'Menulis: Jurnal Penelitian Nusantara' : 'Writing: Nusantara Research Journal' }}</div>
                    <div class="subtitle">HP: {{ $publisher->phone ?? '+62 851-5862-9831' }} | E-Mail: {{ $publisher->email ?? 'padang.tekno.corp@gmail.com' }}</div>
                    <div class="subtitle">E-ISSN: {{ $journal->e_issn ?? '3018-683X' }}</div>
                </td>
                
                <td class="header-right">
                    @if(isset($journal->logo) && $journal->logo)
                        <img src="{{ public_path('storage/' . $journal->logo) }}" alt="Publisher Logo" class="publisher-logo">
                    @else
                        <div class="publisher-logo" style="border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">LOGO</div>
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
                <em>Letter Of Accepted (LoA)</em>
            @else
                LETTER OF ACCEPTED (LOA)<br>
                <em>Surat Keterangan Publikasi Artikel Jurnal</em>
            @endif
        </h2>
    </div>
    
    <!-- LOA Number -->
    <div class="loa-number">
        {{ $lang == 'id' ? 'Nomor' : 'Number' }}: {{ $loa->loa_code ?? '711/Menulis-Vol.1/No.8/2025' }}
    </div>
    
    <!-- Recipient -->
    <div class="recipient">
        <strong>{{ $lang == 'id' ? 'Kepada Yth.' : 'To' }}:</strong><br>
        {{ $request->author ?? 'Andraal Celvin, Ruth Deby Sarvalistia, Khairunisa' }}
    </div>
    
    <!-- Content -->
    <div class="content">
        @if($lang == 'id')
            <p><strong>Terimakasih</strong> telah mengirimkan artikel terbaik anda untuk diterbitkan pada <strong>Menulis: Jurnal Penelitian Nusantara</strong> dengan judul:</p>
        @else
            <p><strong>Thank you</strong> for submitting your best article for publication in <strong>Menulis: Jurnal Penelitian Nusantara</strong> with the title:</p>
        @endif
    </div>
    
    <!-- Article Title -->
    <div class="article-title">
        "{{ $request->article_title ?? 'Perancangan Media Informasi Desa Wisata Partungko Naginjang Kabupaten Samosir Melalui Media Website' }}"
    </div>
    
    <!-- Acceptance Statement -->
    <div class="acceptance-statement">
        @if($lang == 'id')
            <p>Berdasarkan hasil review dan keputusan tim editor, maka artikel tersebut dinyatakan <strong>DITERIMA</strong> untuk dipublikasikan pada <strong>Menulis: Jurnal Penelitian Nusantara</strong> edisi <strong>Volume {{ $request->volume ?? '1' }} Nomor {{ $request->number ?? '8' }}</strong> bulan <strong>{{ $request->month ?? 'Agustus' }} {{ $request->year ?? '2025' }}</strong>.</p>
            
            <p>Demikian surat keterangan ini kami sampaikan untuk dipergunakan sebagaimana mestinya, kami ucapkan terimakasih.</p>
        @else
            <p>Based on the review results and editorial team decision, the article is declared <strong>ACCEPTED</strong> for publication in <strong>Menulis: Jurnal Penelitian Nusantara</strong> edition <strong>Volume {{ $request->volume ?? '1' }} Number {{ $request->number ?? '8' }}</strong> in <strong>{{ $request->month ?? 'August' }} {{ $request->year ?? '2025' }}</strong>.</p>
            
            <p>This letter of acceptance is issued for proper use as needed, thank you.</p>
        @endif
    </div>
    
    <!-- Registration Number -->
    <div class="reg-number">
        <strong>{{ $lang == 'id' ? 'No Reg' : 'Reg No' }}: {{ $request->no_reg ?? 'LOA320580821323411' }}</strong>
    </div>
    
    <!-- Footer Section with QR Code and Signature -->
    <div class="footer-section">
        <table class="footer-table">
            <tr>
                <td class="qr-section">
                    <!-- QR Code -->
                    @if(isset($qrCode))
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
                    @else
                        <div class="qr-code" style="border: 1px solid #000; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                            QR CODE<br>
                            {{ $loa->loa_code ?? 'LOA-CODE' }}
                        </div>
                    @endif
                    
                    <div class="verification-info">
                        <strong>{{ $lang == 'id' ? 'Keaslian LOA Dapat' : 'LOA Authenticity Can' }}</strong><br>
                        <strong>{{ $lang == 'id' ? 'Diverifikasi Dengan' : 'Be Verified With' }}</strong><br>
                        <strong>{{ $lang == 'id' ? 'Memindai QR Code' : 'Scanning QR Code' }}</strong><br>
                        <strong>{{ $lang == 'id' ? 'Disamping!' : 'Beside!' }}</strong>
                    </div>
                    
                    <div style="margin-top: 10px; font-size: 9px;">
                        {{ $loa->loa_code ?? 'LOA320580821323411' }}
                    </div>
                </td>
                
                <td class="signature-section">
                    <div class="signature-date">
                        {{ isset($request->approved_at) ? $request->approved_at->format('d F Y') : (date('d') . ' ' . 
                            ($lang == 'id' ? 
                                ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][date('n')-1] : 
                                date('F')
                            ) . ' ' . date('Y')
                        ) }}
                    </div>
                    
                    <div class="signature-title">
                        {{ $lang == 'id' ? 'Editor In Chief' : 'Editor In Chief' }}
                    </div>
                    
                    <!-- Signature Space -->
                    <div class="signature-space">
                        @if(isset($journal->signature_image))
                            <img src="{{ public_path('storage/' . $journal->signature_image) }}" alt="Signature" style="max-height: 50px;">
                        @endif
                    </div>
                    
                    <!-- Chief Editor Name with underline -->
                    <div class="signature-name">
                        {{ $journal->chief_editor ?? 'Mardalius, M.Kom' }}
                    </div>
                    
                    <div class="editor-title">
                        {{ $journal->chief_editor ?? 'Mardalius, M.Kom' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Publisher Info -->
    <div class="publisher-info">
        <strong>{{ $lang == 'id' ? 'Penerbit' : 'Publisher' }}:</strong><br>
        <strong>{{ $publisher->name ?? 'PT. Padang Tekno Corp' }}</strong><br>
        {{ $publisher->address ?? 'Jl. Bandar Purus Nauli, Sumatera Utara.' }}<br>
        {{ $publisher->phone ?? '+62 851-5862-9831' }}<br>
        {{ $publisher->email ?? 'padang.tekno.corp@gmail.com' }}
    </div>
</body>
</html>
