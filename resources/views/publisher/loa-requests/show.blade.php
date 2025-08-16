@extends('publisher.layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Request Details Card -->
            <div class="card shadow-lg">
                <div class="card-header {{ $loaRequest->status === 'pending' ? 'bg-warning' : ($loaRequest->status === 'approved' ? 'bg-success' : 'bg-danger') }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Detail Permintaan LOA
                            </h4>
                            <p class="mb-0 mt-2">No. Registrasi: {{ $loaRequest->no_reg ?? '-' }}</p>
                        </div>
                        <div>
                            @if($loaRequest->status === 'pending')
                                <span class="badge bg-light text-dark fs-6">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @elseif($loaRequest->status === 'approved')
                                <span class="badge bg-light text-dark fs-6">
                                    <i class="fas fa-check me-1"></i>Disetujui
                                </span>
                            @else
                                <span class="badge bg-light text-dark fs-6">
                                    <i class="fas fa-times me-1"></i>Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status Alert -->
                    @if($loaRequest->status === 'pending')
                        <div class="alert alert-warning d-flex align-items-center mb-4">
                            <i class="fas fa-clock me-3 fs-4"></i>
                            <div>
                                <h5 class="mb-1">Menunggu Review</h5>
                                <p class="mb-0">Permintaan belum direview admin</p>
                            </div>
                        </div>
                    @elseif($loaRequest->status === 'approved')
                        <div class="alert alert-success d-flex align-items-center mb-4">
                            <i class="fas fa-check-circle me-3 fs-4"></i>
                            <div>
                                <h5 class="mb-1">Permintaan Disetujui</h5>
                                <p class="mb-0">LOA telah disetujui dan tersedia untuk diunduh</p>
                                @if($loaRequest->loaValidated)
                                    <strong>Kode LOA: {{ $loaRequest->loaValidated->loa_code }}</strong>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                            <div>
                                <h5 class="mb-1">Permintaan Ditolak</h5>
                                <p class="mb-0">Mohon periksa data dan kirim ulang permintaan</p>
                            </div>
                        </div>
                    @endif

                    <!-- Article Information -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-newspaper me-2"></i>Informasi Artikel
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-heading me-1"></i>Judul Artikel:
                                    </label>
                                    <p class="form-control-plaintext">{{ $loaRequest->article_title ?? $loaRequest->title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-hashtag me-1"></i>ID Artikel:
                                    </label>
                                    <p class="form-control-plaintext">{{ $loaRequest->article_id ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-users me-1"></i>Penulis:
                                    </label>
                                    <p class="form-control-plaintext">{{ $loaRequest->authors ?? $loaRequest->author ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-envelope me-1"></i>Email:
                                    </label>
                                    <p class="form-control-plaintext">
                                        <a href="mailto:{{ $loaRequest->corresponding_email ?? $loaRequest->author_email ?? '' }}" class="text-decoration-none">
                                            {{ $loaRequest->corresponding_email ?? $loaRequest->author_email ?? '-' }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-edit me-1"></i>Edisi:
                                    </label>
                                    <p class="form-control-plaintext">Volume : {{ $loaRequest->volume ?? '-' }} Nomor: {{ $loaRequest->number ?? '-' }} November {{ $loaRequest->year ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Journal Information -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-book me-2"></i>Informasi Journal
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-journal-whills me-1"></i>Nama Jurnal:
                                    </label>
                                    <p class="form-control-plaintext">{{ optional($loaRequest->journal)->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-building me-1"></i>Penerbit:
                                    </label>
                                    <p class="form-control-plaintext">{{ optional(optional($loaRequest->journal)->publisher)->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-user-edit me-1"></i>Editor-in-Chief:
                                    </label>
                                    <p class="form-control-plaintext">{{ optional($loaRequest->journal)->editor_in_chief ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-fingerprint me-1"></i>ISSN:
                                    </label>
                                    <p class="form-control-plaintext">
                                        @if(optional($loaRequest->journal)->e_issn)
                                            <span class="badge bg-info">E-ISSN: {{ $loaRequest->journal->e_issn }}</span>
                                        @endif
                                        @if(optional($loaRequest->journal)->p_issn)
                                            <span class="badge bg-secondary">P-ISSN: {{ $loaRequest->journal->p_issn }}</span>
                                        @endif
                                        @if(!optional($loaRequest->journal)->e_issn && !optional($loaRequest->journal)->p_issn)
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if(optional($loaRequest->journal)->website)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-globe me-1"></i>Website:
                                    </label>
                                    <p class="form-control-plaintext">
                                        <a href="{{ $loaRequest->journal->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>Kunjungi Website
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Timeline -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-clock me-2"></i>Timeline
                        </h5>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Permintaan Dibuat</h6>
                                    <p class="text-muted mb-0">{{ $loaRequest->created_at ? $loaRequest->created_at->format('d F Y, H:i') : '-' }}</p>
                                </div>
                            </div>
                            
                            @if($loaRequest->status === 'approved' && $loaRequest->loaValidated)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Permintaan Disetujui</h6>
                                    <p class="text-muted mb-0">{{ $loaRequest->loaValidated->created_at ? $loaRequest->loaValidated->created_at->format('d F Y, H:i') : '-' }}</p>
                                </div>
                            </div>
                            @elseif($loaRequest->status === 'rejected')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Permintaan Ditolak</h6>
                                    <p class="text-muted mb-0">{{ $loaRequest->updated_at ? $loaRequest->updated_at->format('d F Y, H:i') : '-' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar
                        </a>
                        
                        <div>
                            @if($loaRequest->status === 'pending')
                                <!-- Approve/Reject buttons for pending requests -->
                                <form action="{{ route('publisher.loa-requests.approve', $loaRequest) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success me-2" onclick="return confirm('Apakah Anda yakin ingin menyetujui LOA request ini?')">
                                        <i class="fas fa-check me-1"></i>Setujui Permintaan
                                    </button>
                                </form>
                                
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-1"></i>Tolak Permintaan
                                </button>
                            @elseif($loaRequest->status === 'approved' && $loaRequest->loaValidated)
                                <!-- Download buttons for approved requests -->
                                <a href="{{ route('loa.download', $loaRequest->loaValidated->loa_code) }}" class="btn btn-success me-2" target="_blank">
                                    <i class="fas fa-download me-1"></i>Download LOA
                                </a>
                                <a href="{{ route('loa.verify', $loaRequest->loaValidated->loa_code) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-qrcode me-1"></i>Verifikasi QR
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Permintaan LOA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('publisher.loa-requests.reject', $loaRequest) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -18px;
    top: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}
</style>
@endsection
                        </tr>
                        <tr>
                            <th>Penulis</th>
                            <td>{{ $loaRequest->authors ?? $loaRequest->author ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email Koresponden</th>
                            <td>{{ $loaRequest->corresponding_email ?? $loaRequest->author_email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Journal</th>
                            <td>{{ optional($loaRequest->journal)->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Publisher</th>
                            <td>{{ optional(optional($loaRequest->journal)->publisher)->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Volume / No / Tahun</th>
                            <td>{{ $loaRequest->volume ?? '-' }} / {{ $loaRequest->number ?? '-' }} / {{ $loaRequest->year ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>{{ optional($loaRequest->created_at)->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-3 d-flex gap-2">
                        @if(($loaRequest->status ?? '') === 'approved')
                            <a href="{{ route('publisher.loa-requests.download', $loaRequest->id) }}" class="btn btn-success">Download LOA</a>
                        @endif
                        <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                            </div>
                            @endif
                            
                            @if($loaRequest->status === 'approved' && $loaRequest->loaValidated)
                            <div class="d-grid gap-2">
                                <a href="{{ route('loa.view', [$loaRequest->loaValidated->loa_code, 'id']) }}" 
                                   target="_blank" 
                                   class="btn btn-primary">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat LOA (ID)
                                </a>
                                <a href="{{ route('loa.view', [$loaRequest->loaValidated->loa_code, 'en']) }}" 
                                   target="_blank" 
                                   class="btn btn-info">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat LOA (EN)
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($loaRequest->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('publisher.loa-requests.reject', $loaRequest) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Permintaan LOA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda akan menolak permintaan LOA untuk artikel "<strong>{{ $loaRequest->article_title }}</strong>"
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="admin_notes" 
                                  name="admin_notes" 
                                  rows="4" 
                                  placeholder="Berikan alasan penolakan yang jelas dan konstruktif..."
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>
                        Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-item i {
        position: absolute;
        left: -22px;
        top: 0;
        background: white;
        padding: 2px;
        border-radius: 50%;
    }
    
    .timeline-content {
        padding-left: 20px;
    }
</style>
@endpush
@endsection