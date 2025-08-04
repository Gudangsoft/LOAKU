@extends('layouts.app')

@section('title', 'Hasil Verifikasi QR Code - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($loa)
                <!-- Success Card -->
                <div class="card shadow-lg border-success">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-1">LOA Terverifikasi</h4>
                                <p class="mb-0">Letter of Acceptance ditemukan dan valid</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- QR Code Info -->
                        <div class="alert alert-info">
                            <i class="fas fa-qrcode me-2"></i>
                            <strong>Verifikasi melalui QR Code berhasil!</strong>
                            <br>Kode LOA: <strong>{{ $loa->loa_code }}</strong>
                        </div>

                        <!-- LOA Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group mb-3">
                                    <label class="info-label">
                                        <i class="fas fa-certificate text-success me-2"></i>
                                        Kode LOA
                                    </label>
                                    <div class="info-value">
                                        <span class="badge bg-success fs-6">{{ $loa->loa_code }}</span>
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label">
                                        <i class="fas fa-hashtag text-primary me-2"></i>
                                        No. Registrasi
                                    </label>
                                    <div class="info-value">{{ $loa->loaRequest->no_reg }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label">
                                        <i class="fas fa-calendar-check text-info me-2"></i>
                                        Tanggal Validasi
                                    </label>
                                    <div class="info-value">{{ $loa->created_at->format('d F Y, H:i') }} WIB</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-group mb-3">
                                    <label class="info-label">
                                        <i class="fas fa-user text-secondary me-2"></i>
                                        Penulis
                                    </label>
                                    <div class="info-value">{{ $loa->loaRequest->author }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label">
                                        <i class="fas fa-book text-info me-2"></i>
                                        Jurnal
                                    </label>
                                    <div class="info-value">{{ $loa->loaRequest->journal->name ?? 'N/A' }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label">
                                        <i class="fas fa-building text-warning me-2"></i>
                                        Penerbit
                                    </label>
                                    <div class="info-value">{{ $loa->loaRequest->journal->publisher->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Article Title -->
                        <div class="info-group mb-4">
                            <label class="info-label">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                Judul Artikel
                            </label>
                            <div class="info-value">
                                <div class="bg-light p-3 rounded">
                                    {{ $loa->loaRequest->article_title }}
                                </div>
                            </div>
                        </div>

                        <!-- Abstract (if available) -->
                        @if($loa->loaRequest->abstract)
                        <div class="info-group mb-4">
                            <label class="info-label">
                                <i class="fas fa-align-left text-secondary me-2"></i>
                                Abstrak
                            </label>
                            <div class="info-value">
                                <div class="bg-light p-3 rounded">
                                    {{ Str::limit($loa->loaRequest->abstract, 300) }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 flex-wrap justify-content-center mt-4">
                            <a href="{{ route('loa.view', [$loa->loa_code, 'id']) }}" 
                               class="btn btn-outline-primary" 
                               target="_blank">
                                <i class="fas fa-eye me-1"></i>
                                Lihat LOA (ID)
                            </a>
                            <a href="{{ route('loa.view', [$loa->loa_code, 'en']) }}" 
                               class="btn btn-outline-primary" 
                               target="_blank">
                                <i class="fas fa-eye me-1"></i>
                                View LOA (EN)
                            </a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i>
                                    Download PDF
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('loa.print', [$loa->loa_code, 'id']) }}">
                                            <i class="fas fa-flag me-2"></i>Bahasa Indonesia
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('loa.print', [$loa->loa_code, 'en']) }}">
                                            <i class="fas fa-flag-usa me-2"></i>English
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Info -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="text-success mb-1">
                                    <i class="fas fa-shield-check me-2"></i>
                                    Dokumen ini telah terverifikasi
                                </h6>
                                <p class="text-muted mb-0">
                                    LOA ini adalah dokumen resmi yang telah divalidasi oleh sistem LOA Management.
                                    Verifikasi dilakukan pada {{ now()->format('d F Y, H:i') }} WIB.
                                </p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="d-flex justify-content-center">
                                    <img src="{{ route('loa.qr', $loa->loa_code) }}" 
                                         alt="QR Code {{ $loa->loa_code }}"
                                         class="img-fluid"
                                         style="max-width: 80px;">
                                </div>
                                <small class="text-muted">QR Code Verifikasi</small>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Error Card -->
                <div class="card shadow-lg border-danger">
                    <div class="card-header bg-danger text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-times-circle fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-1">LOA Tidak Ditemukan</h4>
                                <p class="mb-0">Kode LOA dari QR Code tidak valid atau tidak terdaftar</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning opacity-50"></i>
                        </div>
                        
                        <h5 class="text-danger mb-3">Verifikasi QR Code Gagal</h5>
                        <p class="text-muted mb-4">
                            Kode LOA yang Anda scan tidak ditemukan dalam database sistem. 
                            Pastikan QR Code yang Anda scan adalah QR Code LOA yang valid.
                        </p>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>
                                Kemungkinan Penyebab:
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-minus me-2"></i>QR Code bukan dari sistem LOA Management</li>
                                <li><i class="fas fa-minus me-2"></i>LOA belum divalidasi oleh admin</li>
                                <li><i class="fas fa-minus me-2"></i>Kode LOA sudah expired atau dihapus</li>
                                <li><i class="fas fa-minus me-2"></i>QR Code rusak atau tidak terbaca dengan benar</li>
                            </ul>
                            @if(isset($searchCode))
                                <hr class="my-2">
                                <small class="text-muted">
                                    <i class="fas fa-search me-1"></i>
                                    Kode yang dicari: <code>{{ $searchCode }}</code>
                                </small>
                            @endif
                        </div>

                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('qr.scanner') }}" class="btn btn-primary">
                                <i class="fas fa-qrcode me-1"></i>
                                Scan Ulang
                            </a>
                            <a href="{{ route('loa.verify') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-keyboard me-1"></i>
                                Input Manual
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Navigation -->
            <div class="text-center mt-4">
                <a href="{{ route('qr.scanner') }}" class="btn btn-outline-info me-2">
                    <i class="fas fa-qrcode me-1"></i>
                    Scan QR Lain
                </a>
                <a href="{{ route('loa.validated') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-list me-1"></i>
                    LOA Tervalidasi
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-1"></i>
                    Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.info-group {
    margin-bottom: 1rem;
}

.info-label {
    font-weight: 600;
    color: #495057;
    display: block;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.info-value {
    color: #212529;
    font-size: 1rem;
}

.card {
    border-radius: 12px;
}

.btn {
    border-radius: 6px;
}
</style>
@endsection
