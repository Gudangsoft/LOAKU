@extends('layouts.app')

@section('title', 'Detail Permintaan LOA - Admin')

@section('content')
<div class="container py-5">
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
                            <p class="mb-0 mt-2">No. Registrasi: {{ $loaRequest->no_reg }}</p>
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
                
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Article Information -->
                            <h5 class="text-primary mb-3">Informasi Artikel</h5>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-file-alt me-2 text-primary"></i>Judul Artikel:</strong>
                                <p class="mt-1 mb-0">{{ $loaRequest->article_title }}</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-hashtag me-2 text-primary"></i>ID Artikel:</strong><br>
                                        {{ $loaRequest->article_id }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-id-card me-2 text-primary"></i>No. Registrasi:</strong><br>
                                        <span class="badge bg-secondary">{{ $loaRequest->no_reg }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-user me-2 text-primary"></i>Penulis:</strong><br>
                                        {{ $loaRequest->author }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-envelope me-2 text-primary"></i>Email:</strong><br>
                                        <a href="mailto:{{ $loaRequest->author_email }}">{{ $loaRequest->author_email }}</a>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar me-2 text-primary"></i>Edisi:</strong><br>
                                {{ $loaRequest->edition }}
                            </div>
                            
                            <!-- Journal Information -->
                            <h5 class="text-primary mb-3 mt-4">Informasi Jurnal</h5>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-journal-whills me-2 text-primary"></i>Nama Jurnal:</strong><br>
                                        {{ $loaRequest->journal->name }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-building me-2 text-primary"></i>Penerbit:</strong><br>
                                        {{ $loaRequest->journal->publisher->name }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-user-tie me-2 text-primary"></i>Editor-in-Chief:</strong><br>
                                        {{ $loaRequest->journal->chief_editor }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-hashtag me-2 text-primary"></i>ISSN:</strong><br>
                                        @if($loaRequest->journal->e_issn)
                                            <span class="badge bg-info me-1">E-ISSN: {{ $loaRequest->journal->e_issn }}</span>
                                        @endif
                                        @if($loaRequest->journal->p_issn)
                                            <span class="badge bg-secondary">P-ISSN: {{ $loaRequest->journal->p_issn }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($loaRequest->journal->website)
                            <div class="mb-3">
                                <strong><i class="fas fa-globe me-2 text-primary"></i>Website:</strong><br>
                                <a href="{{ $loaRequest->journal->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Kunjungi Website
                                </a>
                            </div>
                            @endif
                            
                            <!-- Timeline -->
                            <h5 class="text-primary mb-3 mt-4">Timeline</h5>
                            
                            <div class="timeline">
                                <div class="timeline-item">
                                    <i class="fas fa-plus-circle text-info"></i>
                                    <div class="timeline-content">
                                        <strong>Permintaan Dibuat</strong>
                                        <p class="mb-0 text-muted">{{ $loaRequest->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                                
                                @if($loaRequest->approved_at)
                                <div class="timeline-item">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <div class="timeline-content">
                                        <strong>Disetujui Admin</strong>
                                        <p class="mb-0 text-muted">{{ $loaRequest->approved_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($loaRequest->status === 'rejected')
                                <div class="timeline-item">
                                    <i class="fas fa-times-circle text-danger"></i>
                                    <div class="timeline-content">
                                        <strong>Ditolak</strong>
                                        <p class="mb-0 text-muted">{{ $loaRequest->updated_at->format('d F Y, H:i') }}</p>
                                        @if($loaRequest->admin_notes)
                                            <p class="mb-0 mt-1"><em>"{{ $loaRequest->admin_notes }}"</em></p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Status Card -->
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body text-center">
                                    @if($loaRequest->status === 'pending')
                                        <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                                        <h5 class="text-warning">Menunggu Review</h5>
                                        <p class="text-muted">Permintaan belum direview admin</p>
                                    @elseif($loaRequest->status === 'approved')
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5 class="text-success">Disetujui</h5>
                                        <p class="text-muted">LOA telah divalidasi dan tersedia</p>
                                        @if($loaRequest->loaValidated)
                                            <div class="mt-2">
                                                <span class="badge bg-success">Kode LOA: {{ $loaRequest->loaValidated->loa_code }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                        <h5 class="text-danger">Ditolak</h5>
                                        <p class="text-muted">Permintaan tidak dapat disetujui</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            @if($loaRequest->status === 'pending')
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.loa-requests.approve', $loaRequest) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin menyetujui permintaan ini?')">
                                        <i class="fas fa-check me-1"></i>
                                        Setujui Permintaan
                                    </button>
                                </form>
                                
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-1"></i>
                                    Tolak Permintaan
                                </button>
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
                <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-secondary">
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
            <form action="{{ route('admin.loa-requests.reject', $loaRequest) }}" method="POST">
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
