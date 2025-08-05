@extends('layouts.app')

@section('title', 'Cari & Download LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-search text-primary me-2"></i>
                        Cari & Download LOA
                    </h2>
                    <p class="text-muted mb-0">Cari dan unduh Letter of Acceptance yang telah divalidasi</p>
                </div>
                <div>
                    <a href="{{ route('loa.verify') }}" class="btn btn-warning">
                        <i class="fas fa-shield-alt me-1"></i>
                        Verifikasi LOA
                    </a>
                </div>
            </div>

            <!-- Alerts for messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ $validatedLoas->total() }}</h5>
                                    <p class="card-text">Total LOA Tervalidasi</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-certificate fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ $validatedLoas->where('created_at', '>=', now()->subDays(30))->count() }}</h5>
                                    <p class="card-text">Validasi Bulan Ini</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ $validatedLoas->where('created_at', '>=', now()->subDays(7))->count() }}</h5>
                                    <p class="card-text">Validasi Minggu Ini</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Form Section -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>
                        Cari LOA Tervalidasi
                    </h5>
                </div>
                <div class="card-body search-form">
                    <form method="GET" action="{{ route('loa.validated') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search_loa_code" class="form-label">Kode LOA</label>
                            <input type="text"
                                   class="form-control"
                                   id="search_loa_code"
                                   name="loa_code"
                                   value="{{ request('loa_code') }}"
                                   placeholder="Contoh: LOA20250801030918">
                        </div>
                        <div class="col-md-4">
                            <label for="search_title" class="form-label">Judul Artikel</label>
                            <input type="text"
                                   class="form-control"
                                   id="search_title"
                                   name="title"
                                   value="{{ request('title') }}"
                                   placeholder="Cari berdasarkan judul artikel">
                        </div>
                        <div class="col-md-4">
                            <label for="search_author" class="form-label">Penulis</label>
                            <input type="text"
                                   class="form-control"
                                   id="search_author"
                                   name="author"
                                   value="{{ request('author') }}"
                                   placeholder="Nama penulis">
                        </div>
                        <div class="col-md-3">
                            <label for="search_journal" class="form-label">Jurnal</label>
                            <select class="form-select" id="search_journal" name="journal_id">
                                <option value="">Semua Jurnal</option>
                                @if(isset($journals))
                                    @foreach($journals as $journal)
                                        <option value="{{ $journal->id }}" {{ request('journal_id') == $journal->id ? 'selected' : '' }}>
                                            {{ $journal->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search_year" class="form-label">Tahun</label>
                            <select class="form-select" id="search_year" name="year">
                                <option value="">Semua Tahun</option>
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search_sort" class="form-label">Urutkan</label>
                            <select class="form-select" id="search_sort" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="loa_code" {{ request('sort') == 'loa_code' ? 'selected' : '' }}>Kode LOA</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    Cari
                                </button>
                                <a href="{{ route('loa.validated') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-refresh me-1"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Results Info -->
            @if(request()->hasAny(['loa_code', 'title', 'author', 'journal_id', 'year']))
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Hasil Pencarian:</strong>
                        Ditemukan {{ $validatedLoas->total() }} LOA
                        @if(request('loa_code'))
                            dengan kode "<strong>{{ request('loa_code') }}</strong>"
                        @endif
                        @if(request('title'))
                            dengan judul mengandung "<strong>{{ request('title') }}</strong>"
                        @endif
                        @if(request('author'))
                            dari penulis "<strong>{{ request('author') }}</strong>"
                        @endif
                    </div>
                </div>
            @endif

            @if($validatedLoas->count() > 0)
                <!-- LOA Cards -->
                <div class="row">
                    @foreach($validatedLoas as $loa)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">
                                            <i class="fas fa-certificate me-1"></i>
                                            {{ $loa->loa_code }}
                                        </h6>
                                    </div>
                                    <div>
                                        <span class="badge bg-light text-success">
                                            <i class="fas fa-check me-1"></i>
                                            Tervalidasi
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title text-truncate" title="{{ $loa->loaRequest->article_title }}">
                                        {{ Str::limit($loa->loaRequest->article_title, 60) }}
                                    </h6>

                                    <div class="row text-sm mb-3">
                                        <div class="col-12 mb-2">
                                            <strong><i class="fas fa-user me-1 text-primary"></i>Penulis:</strong><br>
                                            <span class="text-muted">{{ $loa->loaRequest->author }}</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong><i class="fas fa-book me-1 text-info"></i>Jurnal:</strong><br>
                                            <span class="text-muted">{{ $loa->loaRequest->journal->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong><i class="fas fa-building me-1 text-warning"></i>Penerbit:</strong><br>
                                            <span class="text-muted">{{ $loa->loaRequest->journal->publisher->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <div class="row text-sm mb-3">
                                        <div class="col-6">
                                            <strong class="text-success">No. Reg:</strong><br>
                                            <small class="badge bg-light text-dark">{{ $loa->loaRequest->no_reg }}</small>
                                        </div>
                                        <div class="col-6">
                                            <strong class="text-success">Dibuat:</strong><br>
                                            <small class="text-muted">{{ $loa->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-light">
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ route('loa.view', [$loa->loa_code, 'id']) }}"
                                           class="btn btn-sm btn-outline-primary flex-grow-1"
                                           target="_blank">
                                            <i class="fas fa-eye me-1"></i>
                                            Lihat
                                        </a>
                                        <div class="btn-group flex-grow-1">
                                            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="fas fa-download me-1"></i>
                                                Download LOA
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('loa.print', [$loa->loa_code, 'id']) }}" target="_blank">
                                                        <i class="fas fa-flag me-2"></i>Bahasa Indonesia
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('loa.print', [$loa->loa_code, 'en']) }}" target="_blank">
                                                        <i class="fas fa-flag-usa me-2"></i>English
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- QR Code Section -->
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#qrModal{{ $loop->index }}">
                                            <i class="fas fa-qrcode me-1"></i>
                                            Lihat QR
                                        </button>
                                        <div class="btn-group flex-grow-1">
                                            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="fas fa-download me-1"></i>
                                                Download QR
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="javascript:void(0)" 
                                                       onclick="downloadQrImage('{{ route('qr.download', $loa->loa_code) }}')">
                                                        <i class="fas fa-qrcode me-2"></i>QR Code PNG
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('qr.download', $loa->loa_code) }}" 
                                                       target="_blank">
                                                        <i class="fas fa-external-link-alt me-2"></i>Buka di Tab Baru
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- QR Code Modal -->
                                    <div class="modal fade" id="qrModal{{ $loop->index }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h6 class="modal-title">
                                                        <i class="fas fa-qrcode me-2"></i>
                                                        QR Code - {{ $loa->loa_code }}
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center p-4">
                                                    <!-- Loading Spinner -->
                                                    <div id="qrLoading{{ $loop->index }}" class="mb-3">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <p class="text-muted mt-2">Memuat QR Code...</p>
                                                    </div>

                                                    <!-- QR Code Container -->
                                                    <div id="qrContainer{{ $loop->index }}" class="mb-3" style="display: none;">
                                                        <div class="qr-code-wrapper p-3 bg-white border rounded shadow-sm d-inline-block">
                                                            <img id="qrImage{{ $loop->index }}"
                                                                 alt="QR Code {{ $loa->loa_code }}"
                                                                 class="img-fluid"
                                                                 style="width: 200px; height: 200px; object-fit: contain;">
                                                        </div>
                                                    </div>

                                                    <!-- Error State -->
                                                    <div id="qrError{{ $loop->index }}" class="mb-3" style="display: none;">
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Gagal memuat QR Code
                                                        </div>
                                                    </div>

                                                    <div id="qrDescription{{ $loop->index }}" style="display: none;">
                                                        <p class="text-muted small mb-0">
                                                            <i class="fas fa-mobile-alt me-1"></i>
                                                            Scan QR Code ini untuk memverifikasi LOA {{ $loa->loa_code }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm"
                                                            onclick="downloadQrImage('{{ route('qr.download', $loa->loa_code) }}')">
                                                        <i class="fas fa-download me-1"></i>
                                                        Download QR
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-secondary btn-sm"
                                                            data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i>
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $validatedLoas->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-certificate fa-5x text-muted opacity-50"></i>
                    </div>
                    <h4 class="text-muted">Belum Ada LOA Tervalidasi</h4>
                    <p class="text-muted mb-4">Belum ada Letter of Acceptance yang telah divalidasi dalam sistem.</p>
                    <div>
                        <a href="{{ route('loa.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-1"></i>
                            Ajukan LOA Baru
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-1"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .text-sm {
        font-size: 0.875rem;
    }
    .badge {
        font-size: 0.75rem;
    }

    /* Search Form Styling */
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3) !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .search-form .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .search-form .btn-group .btn {
        border-radius: 0.375rem;
    }

    .search-form .btn-group .btn:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .search-form .btn-group .btn:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .alert-info {
        border-left: 4px solid #17a2b8;
        background-color: #f8f9fa;
        border-color: #bee5eb;
    }

    /* QR Code Modal Styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    .qr-code-wrapper {
        background: #ffffff !important;
        border: 2px solid #dee2e6 !important;
        border-radius: 8px !important;
        padding: 15px !important;
        display: inline-block;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .qr-code-wrapper img {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        background: white !important;
        border: none !important;
    }

    /* Loading Spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Modal Backdrop Fix */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5) !important;
    }

    .modal {
        z-index: 1050 !important;
    }

    .modal-dialog {
        z-index: 1051 !important;
    }

    /* Prevent modal from flickering */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translate(0, -50px);
    }

    .modal.show .modal-dialog {
        transform: none;
    }
</style>
@endpush

@push('scripts')
<script>
// Download QR Code function with better error handling
function downloadQrCode(loaCode) {
    const downloadBtn = event.target.closest('button');
    const originalContent = downloadBtn.innerHTML;

    // Show loading state
    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Downloading...';
    downloadBtn.disabled = true;

    try {
        // Create download link
        const downloadUrl = `/loa/${loaCode}/qr/download`;

        // Method 1: Try direct window.open for download
        const downloadWindow = window.open(downloadUrl, '_blank');

        // Method 2: If that fails, use fetch and blob
        if (!downloadWindow) {
            fetch(downloadUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `QR_${loaCode}.png`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                })
                .catch(error => {
                    console.error('Download failed:', error);
                    alert('Download gagal. Silakan coba lagi.');
                });
        }

    } catch (error) {
        console.error('Download error:', error);
        alert('Terjadi kesalahan saat download. Silakan coba lagi.');
    }

    // Reset button state after delay
    setTimeout(() => {
        downloadBtn.innerHTML = originalContent;
        downloadBtn.disabled = false;
    }, 2000);
}

// Enhanced modal management
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all QR modals
    const qrModals = document.querySelectorAll('[id^="qrModal"]');

    qrModals.forEach(function(modalElement, index) {
        const modalId = modalElement.id;

        // Buat modal tanpa backdrop
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: false, // <--- Hilangkan backdrop
            keyboard: true   // (opsional) biarkan tombol Esc tetap menutup modal
        });

        // Get LOA code from modal title
        const modalTitle = modalElement.querySelector('.modal-title');
        const loaCode = modalTitle ? modalTitle.textContent.split(' - ')[1] : '';

        if (!loaCode) {
            console.error('LOA code not found for modal:', modalId);
            return;
        }

        // Handle modal show event
        modalElement.addEventListener('show.bs.modal', function () {
            resetModalState(modalElement);
        });

        modalElement.addEventListener('shown.bs.modal', function () {
            loadQrCode(modalElement, loaCode);
        });

        // Handle modal hide event
        modalElement.addEventListener('hidden.bs.modal', function () {
            resetModalState(modalElement);
        });

        // Enhanced close button functionality
        const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                modal.hide();
            });
        });
    });
});


// Reset modal state
function resetModalState(modalElement) {
    const qrImage = modalElement.querySelector('[id^="qrImage"]');
    const loadingDiv = modalElement.querySelector('[id^="qrLoading"]');
    const containerDiv = modalElement.querySelector('[id^="qrContainer"]');
    const errorDiv = modalElement.querySelector('[id^="qrError"]');
    const descriptionDiv = modalElement.querySelector('[id^="qrDescription"]');

    if (qrImage) qrImage.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // Transparent pixel
    if (loadingDiv) loadingDiv.style.display = 'block';
    if (containerDiv) containerDiv.style.display = 'none';
    if (errorDiv) errorDiv.style.display = 'none';
    if (descriptionDiv) descriptionDiv.style.display = 'none';
}

// Load QR Code with better error handling
function loadQrCode(modalElement, loaCode) {
    const qrImage = modalElement.querySelector('[id^="qrImage"]');
    const loadingDiv = modalElement.querySelector('[id^="qrLoading"]');
    const containerDiv = modalElement.querySelector('[id^="qrContainer"]');
    const errorDiv = modalElement.querySelector('[id^="qrError"]');
    const descriptionDiv = modalElement.querySelector('[id^="qrDescription"]');

    // Show loading state
    if (loadingDiv) loadingDiv.style.display = 'block';
    if (containerDiv) containerDiv.style.display = 'none';
    if (errorDiv) errorDiv.style.display = 'none';
    if (descriptionDiv) descriptionDiv.style.display = 'none';

    if (!qrImage) {
        console.error('QR Image element not found');
        showError(modalElement);
        return;
    }

    // Create new image to test loading
    const testImage = new Image();

    testImage.onload = function() {
        qrImage.src = this.src;
        setTimeout(() => {
            if (loadingDiv) loadingDiv.style.display = 'none';
            if (containerDiv) containerDiv.style.display = 'block';
            if (descriptionDiv) descriptionDiv.style.display = 'block';
        }, 500); // Smoother transition
    };

    testImage.onerror = function() {
        console.error('Failed to load QR code for:', loaCode);
        showError(modalElement);
    };

    // Load QR code with cache-busting
    const timestamp = new Date().getTime();
    testImage.src = `/loa/${loaCode}/qr?t=${timestamp}`;

    // Timeout fallback
    setTimeout(() => {
        if (loadingDiv && loadingDiv.style.display !== 'none') {
            console.warn('QR loading timeout for:', loaCode);
            showError(modalElement);
        }
    }, 10000); // 10 second timeout
}

// Show error state
function showError(modalElement) {
    const loadingDiv = modalElement.querySelector('[id^="qrLoading"]');
    const errorDiv = modalElement.querySelector('[id^="qrError"]');

    if (loadingDiv) loadingDiv.style.display = 'none';
    if (errorDiv) errorDiv.style.display = 'block';
}

// Download QR image function (with multiple fallbacks)
function downloadQrImage(url) {
    try {
        showNotification('Memulai download QR Code...', 'info');

        // Method 1: Direct download link
        const link = document.createElement('a');
        link.href = url;
        link.download = 'QR_Code_' + Date.now() + '.png';
        link.target = '_blank';
        link.style.display = 'none';
        document.body.appendChild(link);

        // Trigger download
        link.click();

        // Cleanup
        setTimeout(() => {
            document.body.removeChild(link);
        }, 100);

        showNotification('QR Code berhasil didownload!', 'success');

    } catch (error) {
        console.error('Download error:', error);

        // Fallback: Open in new tab
        try {
            window.open(url, '_blank');
            showNotification('QR Code dibuka di tab baru', 'warning');
        } catch (error2) {
            // Last resort: Copy link to clipboard
            copyToClipboard(url);
            showNotification('Link QR Code disalin ke clipboard', 'info');
        }
    }
}

// Alternative download using fetch API
function downloadQrImageAdvanced(loaCode) {
    const apiUrl = '/api/qr/download/' + loaCode;

    showNotification('Generating QR Code...', 'info');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Convert base64 to blob and download
                const link = document.createElement('a');
                link.href = data.data;
                link.download = data.filename;
                link.click();

                showNotification('QR Code berhasil didownload!', 'success');
            } else {
                throw new Error(data.error || 'Download failed');
            }
        })
        .catch(error => {
            console.error('API Download error:', error);
            showNotification('Download gagal: ' + error.message, 'danger');

            // Fallback to direct method
            const fallbackUrl = '/qr/download/' + loaCode;
            downloadQrImage(fallbackUrl);
        });
}

// Copy text to clipboard
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
}

// Close modal function
function closeModal(modalId) {
    try {
        const modal = document.getElementById(modalId);
        if (modal) {
            // Method 1: Try Bootstrap modal hide
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                    return;
                }
            }

            // Method 2: Manual close
            modal.style.display = 'none';
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            modal.removeAttribute('aria-modal');

            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }

            // Restore body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            showNotification('Modal ditutup', 'success');
        }
    } catch (error) {
        // Fallback: Reload page if all fails
        console.error('Error closing modal:', error);
        showNotification('Menutup modal...', 'warning');
        setTimeout(function() {
            location.reload();
        }, 1000);
    }
}

// Show notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(function() {
        if (notification && notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Initialize tooltips when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@endsection
