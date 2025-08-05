@extends('layouts.app')

@section('title', 'Admin Dashboard - LOA Management System SIPTENAN')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard Admin
            </h1>
            <p class="mb-0 text-muted">Selamat datang di panel administrasi LOA Management System SIPTENAN</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar me-1"></i>
            {{ now()->format('d F Y, H:i') }}
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Permintaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRequests) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Validasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingRequests) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                LOA Disetujui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($approvedRequests) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jurnal Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalJournals) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Permintaan Ditolak
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($rejectedRequests) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Data Penerbit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalPublishers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                LOA Tervalidasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($validatedLoas) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Permintaan LOA Terbaru
            </h6>
            <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye me-1"></i>
                Lihat Semua
            </a>
        </div>
        <div class="card-body">
            @if($recentRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Reg</th>
                                <th>No. LOA</th>
                                <th>Judul Artikel</th>
                                <th>Penulis</th>
                                <th>Jurnal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRequests as $request)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $request->no_reg }}</span></td>
                                    <td>
                                        @if($request->status === 'approved')
                                            @php
                                                $validatedLoa = $request->loaValidated;
                                            @endphp
                                            @if($validatedLoa)
                                                <span class="badge bg-success px-2 py-1" 
                                                      title="LOA Terverifikasi: {{ $validatedLoa->loa_code }}"
                                                      data-bs-toggle="tooltip">
                                                    <i class="fas fa-certificate me-1"></i>
                                                    {{ $validatedLoa->loa_code }}
                                                </span>
                                            @else
                                                <span class="text-muted small" title="LOA belum dibuat">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Belum dibuat
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $request->article_title }}">
                                            {{ $request->article_title }}
                                        </div>
                                    </td>
                                    <td>{{ $request->author }}</td>
                                    <td>{{ $request->journal->name }}</td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.loa-requests.show', $request) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($request->status === 'approved' && $request->loaValidated)
                                            <a href="{{ route('admin.loa.validated') }}" 
                                               class="btn btn-success btn-sm ms-1"
                                               title="Lihat LOA">
                                                <i class="fas fa-certificate"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada permintaan LOA</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.loa-requests.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-alt me-2 text-warning"></i>
                            Kelola Permintaan LOA
                            @if($pendingRequests > 0)
                                <span class="badge bg-warning float-end">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.journals.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2 text-success"></i>
                            Tambah Jurnal Baru
                        </a>
                        <a href="{{ route('admin.publishers.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-building me-2 text-info"></i>
                            Tambah Penerbit Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>
                        Status Overview
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $totalRequestsCalc = $totalRequests ?: 1;
                        $pendingPercent = ($pendingRequests / $totalRequestsCalc) * 100;
                        $approvedPercent = ($approvedRequests / $totalRequestsCalc) * 100;
                        $rejectedPercent = ($rejectedRequests / $totalRequestsCalc) * 100;
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <small>Pending</small>
                            <small>{{ $pendingRequests }} ({{ number_format($pendingPercent, 1) }}%)</small>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" style="width: {{ $pendingPercent }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <small>Disetujui</small>
                            <small>{{ $approvedRequests }} ({{ number_format($approvedPercent, 1) }}%)</small>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $approvedPercent }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <div class="d-flex justify-content-between">
                            <small>Ditolak</small>
                            <small>{{ $rejectedRequests }} ({{ number_format($rejectedPercent, 1) }}%)</small>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-danger" style="width: {{ $rejectedPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@push('styles')
<style>
    .table th {
        font-weight: 600;
        color: #5a5c69;
        border-top: none;
    }
    
    .badge.bg-success {
        background: linear-gradient(45deg, #1cc88a, #17a673) !important;
        box-shadow: 0 2px 4px rgba(28, 200, 138, 0.3);
    }
    
    .badge.bg-warning {
        background: linear-gradient(45deg, #f6c23e, #e3a91c) !important;
        box-shadow: 0 2px 4px rgba(246, 194, 62, 0.3);
    }
    
    .badge.bg-danger {
        background: linear-gradient(45deg, #e74a3b, #c82333) !important;
        box-shadow: 0 2px 4px rgba(231, 74, 59, 0.3);
    }
    
    .badge.bg-secondary {
        background: linear-gradient(45deg, #6c757d, #545b62) !important;
        box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .btn-sm {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }
    
    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .table-responsive {
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }
    .border-left-dark {
        border-left: 0.25rem solid #5a5c69 !important;
    }
    .text-xs {
        font-size: .7rem;
    }
</style>
@endpush
@endsection
