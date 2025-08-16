@extends('publisher.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body bg-gradient-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">
                                <i class="fas fa-file-contract me-2"></i>Detail LOA Request
                            </h3>
                            <p class="mb-0 opacity-75">No. Registrasi: {{ $loaRequest->no_reg ?? 'LOASIP.' . $loaRequest->article_id . '.001' }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if($loaRequest->status === 'pending')
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>Menunggu Review
                                </span>
                            @elseif($loaRequest->status === 'approved')
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Article Info -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-newspaper me-2"></i>Informasi Artikel
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">JUDUL ARTIKEL</label>
                                    <p class="mb-0 fs-6">{{ $loaRequest->article_title ?? $loaRequest->title ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">ID ARTIKEL</label>
                                    <p class="mb-0 fs-6 font-monospace text-primary">{{ $loaRequest->article_id ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">PENULIS</label>
                                    <p class="mb-0 fs-6">{{ $loaRequest->authors ?? $loaRequest->author ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">EMAIL KORESPONDEN</label>
                                    <p class="mb-0 fs-6">
                                        @if($loaRequest->corresponding_email ?? $loaRequest->author_email)
                                            <a href="mailto:{{ $loaRequest->corresponding_email ?? $loaRequest->author_email }}" class="text-decoration-none">
                                                {{ $loaRequest->corresponding_email ?? $loaRequest->author_email }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="fw-bold text-muted small">VOLUME / NOMOR / TAHUN</label>
                                    <p class="mb-0 fs-6">
                                        <span class="badge bg-light text-dark me-2">Vol: {{ $loaRequest->volume ?? '-' }}</span>
                                        <span class="badge bg-light text-dark me-2">No: {{ $loaRequest->number ?? '-' }}</span>
                                        <span class="badge bg-light text-dark">{{ $loaRequest->year ?? date('Y') }}</span>
                                    </p>
                                </div>
                            </div>

                            @if($loaRequest->status === 'approved' && $loaRequest->loaValidated)
                            <div class="alert alert-success mt-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-award fa-2x me-3 text-success"></i>
                                    <div>
                                        <h6 class="mb-1">LOA Telah Disetujui!</h6>
                                        <p class="mb-1">Kode LOA: <strong class="text-success">{{ $loaRequest->loaValidated->loa_code }}</strong></p>
                                        <small class="text-muted">Disetujui pada: {{ $loaRequest->loaValidated->created_at->format('d F Y, H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Journal Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-book-open me-2"></i>Informasi Jurnal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">NAMA JURNAL</label>
                                    <p class="mb-0 fs-6 fw-bold">{{ optional($loaRequest->journal)->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">PENERBIT</label>
                                    <p class="mb-0 fs-6">{{ optional(optional($loaRequest->journal)->publisher)->name ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">EDITOR-IN-CHIEF</label>
                                    <p class="mb-0 fs-6">{{ optional($loaRequest->journal)->editor_in_chief ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted small">ISSN</label>
                                    <p class="mb-0">
                                        @if(optional($loaRequest->journal)->e_issn)
                                            <span class="badge bg-info text-white me-1">E-ISSN: {{ $loaRequest->journal->e_issn }}</span>
                                        @endif
                                        @if(optional($loaRequest->journal)->p_issn)
                                            <span class="badge bg-secondary text-white">P-ISSN: {{ $loaRequest->journal->p_issn }}</span>
                                        @endif
                                        @if(!optional($loaRequest->journal)->e_issn && !optional($loaRequest->journal)->p_issn)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if(optional($loaRequest->journal)->website)
                            <div class="row">
                                <div class="col-12">
                                    <label class="fw-bold text-muted small">WEBSITE</label>
                                    <p class="mb-0">
                                        <a href="{{ $loaRequest->journal->website }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>Kunjungi Website
                                        </a>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions & Timeline -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-bolt me-2"></i>Tindakan Cepat
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($loaRequest->status === 'pending')
                                <div class="d-grid gap-2">
                                    <form action="{{ route('publisher.loa-requests.approve', $loaRequest) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui LOA request ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-check me-2"></i>Setujui LOA
                                        </button>
                                    </form>
                                    
                                    <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fas fa-times me-2"></i>Tolak LOA
                                    </button>
                                </div>
                            @elseif($loaRequest->status === 'approved' && $loaRequest->loaValidated)
                                <div class="d-grid gap-2">
                                    <a href="{{ route('loa.download', $loaRequest->loaValidated->loa_code) }}" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-download me-2"></i>Download LOA
                                    </a>
                                    <a href="{{ route('loa.verify', $loaRequest->loaValidated->loa_code) }}" class="btn btn-info" target="_blank">
                                        <i class="fas fa-qrcode me-2"></i>Verifikasi QR
                                    </a>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p class="mb-0">Tidak ada aksi tersedia</p>
                                </div>
                            @endif

                            <hr class="my-3">
                            
                            <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-history me-2"></i>Timeline
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline-simple">
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Permintaan Dibuat</h6>
                                        <small class="text-muted">{{ $loaRequest->created_at->format('d F Y, H:i') }}</small>
                                    </div>
                                </div>
                                
                                @if($loaRequest->status === 'approved')
                                    <div class="timeline-item">
                                        <div class="timeline-dot bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">LOA Disetujui</h6>
                                            <small class="text-muted">
                                                {{ $loaRequest->loaValidated ? $loaRequest->loaValidated->created_at->format('d F Y, H:i') : 'Baru saja' }}
                                            </small>
                                        </div>
                                    </div>
                                @elseif($loaRequest->status === 'rejected')
                                    <div class="timeline-item">
                                        <div class="timeline-dot bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">LOA Ditolak</h6>
                                            <small class="text-muted">{{ $loaRequest->updated_at->format('d F Y, H:i') }}</small>
                                            @if($loaRequest->admin_notes)
                                                <p class="small mb-0 mt-1 text-muted">{{ $loaRequest->admin_notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Tolak Permintaan LOA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('publisher.loa-requests.reject', $loaRequest) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Peringatan:</strong> Tindakan ini akan menolak permintaan LOA. Pastikan Anda memberikan alasan yang jelas.
                    </div>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-bold">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required 
                                  placeholder="Jelaskan alasan penolakan secara detail..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-1"></i>Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.timeline-simple {
    position: relative;
    padding-left: 30px;
}

.timeline-simple::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #007bff, #6c757d);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-dot {
    position: absolute;
    left: -26px;
    top: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endsection
