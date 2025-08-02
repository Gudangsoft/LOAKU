@extends('layouts.app')

@section('title', 'Detail Jurnal - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-book me-2"></i>
                Detail Jurnal
            </h1>
            <p class="mb-0 text-muted">Informasi lengkap jurnal ilmiah</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.journals.edit', $journal) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>
                Edit Jurnal
            </a>
            <a href="{{ route('admin.journals.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Journal Information Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Jurnal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Nama Jurnal:</label>
                            <p class="mb-0 fs-5 fw-semibold">{{ $journal->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Editor-in-Chief:</label>
                            <p class="mb-0">{{ $journal->chief_editor ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">E-ISSN:</label>
                            <p class="mb-0">
                                @if($journal->e_issn)
                                    <span class="badge bg-success">{{ $journal->e_issn }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">P-ISSN:</label>
                            <p class="mb-0">
                                @if($journal->p_issn)
                                    <span class="badge bg-info">{{ $journal->p_issn }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Penerbit:</label>
                            <p class="mb-0">
                                <a href="{{ route('admin.publishers.show', $journal->publisher) }}" 
                                   class="text-decoration-none">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $journal->publisher->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Website:</label>
                            <p class="mb-0">
                                @if($journal->website)
                                    <a href="{{ $journal->website }}" 
                                       target="_blank" 
                                       class="text-decoration-none">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        {{ $journal->website }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Tanggal Dibuat:</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $journal->created_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Terakhir Diupdate:</label>
                            <p class="mb-0">
                                <i class="fas fa-clock me-1"></i>
                                {{ $journal->updated_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo & Signature Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-image me-2"></i>
                        Logo & Tanda Tangan
                    </h6>
                </div>
                <div class="card-body text-center">
                    <!-- Logo -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted d-block">Logo Jurnal:</label>
                        @if($journal->logo)
                            <div class="border rounded p-3 bg-light">
                                <img src="{{ asset('storage/' . $journal->logo) }}" 
                                     alt="Logo {{ $journal->name }}" 
                                     class="img-fluid" 
                                     style="max-height: 150px; max-width: 200px;">
                            </div>
                        @else
                            <div class="border rounded p-4 bg-light text-muted">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p class="mb-0">Tidak ada logo</p>
                            </div>
                        @endif
                    </div>

                    <!-- Signature Stamp -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted d-block">Tanda Tangan & Stempel:</label>
                        @if($journal->ttd_stample)
                            <div class="border rounded p-3 bg-light">
                                <img src="{{ asset('storage/' . $journal->ttd_stample) }}" 
                                     alt="TTD & Stempel {{ $journal->name }}" 
                                     class="img-fluid" 
                                     style="max-height: 150px; max-width: 200px;">
                            </div>
                        @else
                            <div class="border rounded p-4 bg-light text-muted">
                                <i class="fas fa-signature fa-3x mb-2"></i>
                                <p class="mb-0">Tidak ada tanda tangan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistik LOA
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $totalLoa = $journal->loaRequests()->count();
                        $approvedLoa = $journal->loaRequests()->whereHas('loaValidated')->count();
                        $pendingLoa = $journal->loaRequests()->where('status', 'pending')->count();
                        $rejectedLoa = $journal->loaRequests()->where('status', 'rejected')->count();
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total LOA:</span>
                            <span class="badge bg-primary">{{ $totalLoa }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">LOA Disetujui:</span>
                            <span class="badge bg-success">{{ $approvedLoa }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">LOA Pending:</span>
                            <span class="badge bg-warning">{{ $pendingLoa }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">LOA Ditolak:</span>
                            <span class="badge bg-danger">{{ $rejectedLoa }}</span>
                        </div>
                    </div>

                    @if($totalLoa > 0)
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('admin.loa-requests.index', ['journal_id' => $journal->id]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i>
                                Lihat Semua LOA
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent LOA Requests -->
    @if($journal->loaRequests()->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-clock me-2"></i>
                    Permintaan LOA Terbaru (5 terakhir)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode LOA</th>
                                <th>Judul Artikel</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($journal->loaRequests()->latest()->limit(5)->get() as $loa)
                                <tr>
                                    <td>{{ $loa->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <code>{{ $loa->no_reg ?? '-' }}</code>
                                    </td>
                                    <td>
                                        <span title="{{ $loa->article_title }}">
                                            {{ Str::limit($loa->article_title, 50) }}
                                        </span>
                                    </td>
                                    <td>{{ $loa->author }}</td>
                                    <td>
                                        @if($loa->status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Disetujui
                                            </span>
                                        @elseif($loa->status == 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.loa-requests.show', $loa) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Detail LOA">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        border-bottom: none;
    }
    
    .badge {
        font-size: 0.875rem;
    }
    
    .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .table td, .table th {
        vertical-align: middle;
        padding: 0.75rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .border {
        border: 1px solid #dee2e6 !important;
    }
    
    .rounded {
        border-radius: 0.375rem !important;
    }
    
    .img-fluid {
        object-fit: contain;
    }

    .btn-group .btn {
        border-radius: 0.375rem;
    }
    
    .btn-group .btn:not(:last-child) {
        margin-right: 0.5rem;
    }
</style>
@endpush
