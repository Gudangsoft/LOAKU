@extends('layouts.app')

@section('title', ($lang == 'id' ? 'Surat Persetujuan Naskah' : 'Letter of Acceptance') . ' - ' . $loa->loa_code)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <!-- Header Actions -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="fas fa-home me-1"></i>
                                    {{ $lang == 'id' ? 'Beranda' : 'Home' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('loa.validated') }}" class="text-decoration-none">
                                    {{ $lang == 'id' ? 'Cari LOA' : 'Search LOA' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $loa->loa_code }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i>
                        {{ $lang == 'id' ? 'Download PDF' : 'Download PDF' }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('loa.print', [$loa->loa_code, 'id']) }}" target="_blank">
                                <i class="fas fa-flag me-2"></i>
                                {{ $lang == 'id' ? 'Bahasa Indonesia' : 'Indonesian Version' }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('loa.print', [$loa->loa_code, 'en']) }}" target="_blank">
                                <i class="fas fa-flag-usa me-2"></i>
                                {{ $lang == 'id' ? 'English Version' : 'English Version' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Language Toggle -->
            <div class="text-center mb-4">
                <div class="btn-group" role="group">
                    <a href="{{ route('loa.view', [$loa->loa_code, 'id']) }}" 
                       class="btn {{ $lang == 'id' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-flag me-1"></i>
                        Bahasa Indonesia
                    </a>
                    <a href="{{ route('loa.view', [$loa->loa_code, 'en']) }}" 
                       class="btn {{ $lang == 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-flag-usa me-1"></i>
                        English
                    </a>
                </div>
            </div>

            <!-- LOA Certificate Card -->
            <div class="card shadow-lg border-0 loa-certificate">
                <!-- Certificate Header -->
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if($publisher->logo)
                                <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                     alt="Logo {{ $publisher->name }}" 
                                     class="img-fluid publisher-logo">
                            @else
                                <div class="logo-placeholder">
                                    <i class="fas fa-building fa-3x text-white-50"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h2 class="mb-2 fw-bold">{{ $publisher->name }}</h2>
                            <p class="mb-1 text-white-75">{{ $publisher->address ?? '' }}</p>
                            <p class="mb-3 text-white-75">
                                @if($publisher->email)
                                    <i class="fas fa-envelope me-1"></i>{{ $publisher->email }}
                                @endif
                                @if($publisher->phone)
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-phone me-1"></i>{{ $publisher->phone }}
                                @endif
                            </p>
                            <h3 class="certificate-title">
                                @if($lang == 'id')
                                    SURAT PERSETUJUAN NASKAH<br>
                                    <small class="text-white-75">(LETTER OF ACCEPTANCE)</small>
                                @else
                                    LETTER OF ACCEPTANCE<br>
                                    <small class="text-white-75">(SURAT PERSETUJUAN NASKAH)</small>
                                @endif
                            </h3>
                        </div>
                        <div class="col-md-2">
                            @if($journal->logo)
                                <img src="{{ asset('storage/' . $journal->logo) }}" 
                                     alt="Logo {{ $journal->name }}" 
                                     class="img-fluid journal-logo">
                            @else
                                <div class="logo-placeholder">
                                    <i class="fas fa-book fa-3x text-white-50"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- LOA Code Banner -->
                <div class="loa-code-banner text-center py-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-certificate me-2"></i>
                        {{ $lang == 'id' ? 'Kode LOA' : 'LOA Code' }}: {{ $loa->loa_code }}
                    </h4>
                </div>

                <!-- Certificate Body -->
                <div class="card-body p-5">
                    <!-- Introduction -->
                    <div class="text-center mb-4">
                        @if($lang == 'id')
                            <p class="lead mb-4">Dengan ini kami menyatakan bahwa naskah artikel ilmiah berikut:</p>
                        @else
                            <p class="lead mb-4">We hereby declare that the following scientific article manuscript:</p>
                        @endif
                    </div>

                    <!-- Article Information -->
                    <div class="article-details mb-5">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card bg-light border-0">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-alt me-2"></i>
                                            {{ $lang == 'id' ? 'Informasi Artikel' : 'Article Information' }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-bold text-muted" style="width: 30%;">
                                                            <i class="fas fa-heading me-2 text-primary"></i>
                                                            {{ $lang == 'id' ? 'Judul Artikel' : 'Article Title' }}:
                                                        </td>
                                                        <td class="fw-semibold">{{ $request->article_title }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-user me-2 text-success"></i>
                                                            {{ $lang == 'id' ? 'Penulis' : 'Author(s)' }}:
                                                        </td>
                                                        <td>{{ $request->author }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-envelope me-2 text-warning"></i>
                                                            {{ $lang == 'id' ? 'Email Penulis' : 'Author Email' }}:
                                                        </td>
                                                        <td>{{ $request->author_email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-book me-2 text-info"></i>
                                                            {{ $lang == 'id' ? 'Jurnal' : 'Journal' }}:
                                                        </td>
                                                        <td class="fw-semibold">{{ $journal->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-barcode me-2 text-secondary"></i>
                                                            ISSN:
                                                        </td>
                                                        <td>
                                                            @if($journal->e_issn)
                                                                <span class="badge bg-success me-1">E-ISSN: {{ $journal->e_issn }}</span>
                                                            @endif
                                                            @if($journal->p_issn)
                                                                <span class="badge bg-info">P-ISSN: {{ $journal->p_issn }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-list-ol me-2 text-primary"></i>
                                                            {{ $lang == 'id' ? 'Volume/Nomor' : 'Volume/Number' }}:
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-outline-primary">Vol. {{ $request->volume }}</span>
                                                            <span class="badge bg-outline-primary">No. {{ $request->number }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-calendar me-2 text-warning"></i>
                                                            {{ $lang == 'id' ? 'Edisi' : 'Issue' }}:
                                                        </td>
                                                        <td>{{ $request->month }} {{ $request->year }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-hashtag me-2 text-secondary"></i>
                                                            {{ $lang == 'id' ? 'No. Registrasi' : 'Registration No.' }}:
                                                        </td>
                                                        <td><code class="bg-light px-2 py-1 rounded">{{ $request->no_reg }}</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-muted">
                                                            <i class="fas fa-check-circle me-2 text-success"></i>
                                                            {{ $lang == 'id' ? 'Tanggal Persetujuan' : 'Approval Date' }}:
                                                        </td>
                                                        <td class="fw-semibold text-success">
                                                            {{ isset($request->approved_at) ? $request->approved_at->format('d F Y') : $loa->created_at->format('d F Y') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <!-- QR Code -->
                                <div class="card border-0 mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">
                                            <i class="fas fa-qrcode me-2"></i>
                                            {{ $lang == 'id' ? 'Verifikasi QR' : 'QR Verification' }}
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="qr-code-container mb-3">
                                            <img src="{{ route('qr.code', $loa->loa_code) }}" 
                                                 alt="QR Code {{ $loa->loa_code }}"
                                                 class="img-fluid qr-code-image">
                                        </div>
                                        <p class="small text-muted mb-3">
                                            <i class="fas fa-mobile-alt me-1"></i>
                                            {{ $lang == 'id' ? 'Scan untuk verifikasi' : 'Scan to verify' }}
                                        </p>
                                        <div class="btn-group-vertical w-100">
                                            <a href="{{ route('qr.download', $loa->loa_code) }}" 
                                               class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download me-1"></i>
                                                {{ $lang == 'id' ? 'Download QR PNG' : 'Download QR PNG' }}
                                            </a>
                                            <a href="{{ route('qr.download', $loa->loa_code) }}?format=svg" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-vector-square me-1"></i>
                                                {{ $lang == 'id' ? 'Download QR SVG' : 'Download QR SVG' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistics -->
                                <div class="card border-0">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            {{ $lang == 'id' ? 'Informasi Dokumen' : 'Document Info' }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <div class="badge bg-success fs-6 mb-2 w-100 py-2">
                                                <i class="fas fa-check-circle me-1"></i>
                                                {{ $lang == 'id' ? 'TERVALIDASI' : 'VALIDATED' }}
                                            </div>
                                        </div>
                                        <div class="small">
                                            <p class="mb-2">
                                                <strong>{{ $lang == 'id' ? 'Dibuat:' : 'Created:' }}</strong><br>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $loa->created_at->format('d F Y, H:i') }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>{{ $lang == 'id' ? 'Dilihat:' : 'Viewed:' }}</strong><br>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ now()->format('d F Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acceptance Statement -->
                    <div class="acceptance-statement text-center mb-5">
                        <div class="alert alert-success border-0 py-4">
                            <h3 class="fw-bold text-success mb-3">
                                @if($lang == 'id')
                                    <i class="fas fa-check-circle me-2"></i>
                                    TELAH DITERIMA UNTUK DIPUBLIKASIKAN
                                    <br><small class="text-muted">(HAS BEEN ACCEPTED FOR PUBLICATION)</small>
                                @else
                                    <i class="fas fa-check-circle me-2"></i>
                                    HAS BEEN ACCEPTED FOR PUBLICATION
                                    <br><small class="text-muted">(TELAH DITERIMA UNTUK DIPUBLIKASIKAN)</small>
                                @endif
                            </h3>
                        </div>
                        
                        <div class="acceptance-description">
                            @if($lang == 'id')
                                <p class="lead">
                                    Naskah artikel ilmiah telah melalui proses <strong>review</strong> dan telah memenuhi persyaratan untuk dipublikasikan pada jurnal <strong>{{ $journal->name }}</strong>.
                                </p>
                                <p class="text-muted">
                                    Surat persetujuan ini merupakan bukti resmi penerimaan naskah untuk publikasi dan dapat digunakan untuk keperluan akademik dan profesional.
                                </p>
                            @else
                                <p class="lead">
                                    The scientific article manuscript has undergone the <strong>review process</strong> and has met the requirements for publication in <strong>{{ $journal->name }}</strong>.
                                </p>
                                <p class="text-muted">
                                    This letter of acceptance serves as official proof of manuscript acceptance for publication and can be used for academic and professional purposes.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="signature-section">
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-lg-4">
                                <div class="text-center">
                                    <p class="mb-2">
                                        {{ isset($request->approved_at) ? $request->approved_at->format('d F Y') : $loa->created_at->format('d F Y') }}
                                    </p>
                                    <p class="fw-bold mb-3">
                                        {{ $lang == 'id' ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}
                                    </p>
                                    
                                    @if($journal->ttd_stample)
                                        <div class="signature-stamp mb-3">
                                            <img src="{{ asset('storage/' . $journal->ttd_stample) }}" 
                                                 alt="Signature & Stamp" 
                                                 class="img-fluid signature-image">
                                        </div>
                                    @else
                                        <div class="signature-placeholder mb-3">
                                            <div class="border rounded p-3 text-muted">
                                                <i class="fas fa-signature fa-2x mb-2"></i>
                                                <p class="small mb-0">{{ $lang == 'id' ? 'Tanda Tangan & Stempel' : 'Signature & Stamp' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="signature-name">
                                        <div class="border-top border-dark pt-2">
                                            <strong>{{ $journal->chief_editor ?? 'Editor-in-Chief' }}</strong><br>
                                            <small class="text-muted">{{ $lang == 'id' ? 'Pemimpin Redaksi' : 'Editor-in-Chief' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Footer -->
                <div class="card-footer bg-light text-center py-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="verification-info">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    {{ $lang == 'id' ? 'Verifikasi Dokumen' : 'Document Verification' }}
                                </h6>
                                <p class="small text-muted mb-1">
                                    {{ $lang == 'id' ? 'Untuk memverifikasi keaslian dokumen ini:' : 'To verify the authenticity of this document:' }}
                                </p>
                                <p class="small">
                                    <strong>{{ $lang == 'id' ? 'Kunjungi:' : 'Visit:' }}</strong> 
                                    <a href="{{ route('loa.verify') }}" class="text-decoration-none" target="_blank">
                                        {{ url('/verify-loa') }}
                                    </a><br>
                                    <strong>{{ $lang == 'id' ? 'Kode LOA:' : 'LOA Code:' }}</strong> 
                                    <code class="bg-white px-2 py-1 rounded">{{ $loa->loa_code }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-info">
                                <h6 class="fw-bold text-secondary mb-2">
                                    <i class="fas fa-file-alt me-2"></i>
                                    {{ $lang == 'id' ? 'Informasi Sistem' : 'System Information' }}
                                </h6>
                                <p class="small text-muted mb-1">
                                    {{ $lang == 'id' ? 'Dokumen dibuat otomatis oleh:' : 'Document automatically generated by:' }}
                                </p>
                                <p class="small">
                                    <strong>LOA Management System</strong><br>
                                    {{ $lang == 'id' ? 'Dicetak pada:' : 'Generated on:' }} {{ now()->format('d F Y, H:i:s') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Actions -->
            <div class="text-center mt-4">
                <div class="btn-group">
                    <a href="{{ route('loa.validated') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        {{ $lang == 'id' ? 'Kembali ke Pencarian' : 'Back to Search' }}
                    </a>
                    <a href="{{ route('loa.verify') }}" class="btn btn-outline-warning">
                        <i class="fas fa-shield-alt me-1"></i>
                        {{ $lang == 'id' ? 'Verifikasi LOA Lain' : 'Verify Other LOA' }}
                    </a>
                    <button type="button" class="btn btn-outline-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>
                        {{ $lang == 'id' ? 'Cetak Halaman' : 'Print Page' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .loa-certificate {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .certificate-title {
        font-size: 1.5rem;
        line-height: 1.3;
        margin-bottom: 0;
    }
    
    .publisher-logo, .journal-logo {
        max-height: 80px;
        max-width: 120px;
        object-fit: contain;
        background: rgba(255,255,255,0.1);
        padding: 10px;
        border-radius: 8px;
    }
    
    .logo-placeholder {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 8px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .loa-code-banner {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 50%, #f8f9fa 100%);
        border-top: 2px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
    }
    
    .article-details .table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .qr-code-container {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        display: inline-block;
    }
    
    .qr-code-image {
        width: 150px;
        height: 150px;
        object-fit: contain;
    }
    
    .acceptance-statement .alert {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    
    .signature-image {
        max-height: 100px;
        max-width: 200px;
        object-fit: contain;
    }
    
    .signature-placeholder {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .signature-name {
        margin-top: 20px;
    }
    
    .text-white-75 {
        color: rgba(255, 255, 255, 0.75) !important;
    }
    
    @media print {
        .btn-group, .breadcrumb, nav, .card-footer {
            display: none !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .certificate-title {
            font-size: 1.2rem;
        }
        
        .publisher-logo, .journal-logo {
            max-height: 60px;
            max-width: 80px;
        }
        
        .logo-placeholder {
            height: 60px;
            padding: 15px;
        }
        
        .card-body {
            padding: 2rem !important;
        }
    }
    
    /* Animation */
    .loa-certificate {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .badge.bg-outline-primary {
        color: #0d6efd;
        border: 1px solid #0d6efd;
        background-color: transparent;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Handle QR code loading error
    const qrImage = document.querySelector('.qr-code-image');
    if (qrImage) {
        qrImage.addEventListener('error', function() {
            this.parentElement.innerHTML = `
                <div class="text-muted">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <p class="small mb-0">{{ $lang == 'id' ? 'QR Code tidak dapat dimuat' : 'QR Code cannot be loaded' }}</p>
                </div>
            `;
        });
    }
});
</script>
@endpush
