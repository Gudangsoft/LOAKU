@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-gradient-primary text-white rounded">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="h3 mb-0">
                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                            </h1>
                            <p class="mb-0 opacity-75">Sistem Management LOA Artikel Jurnal Ilmiah</p>
                        </div>
                        <div class="col-auto">
                            <div class="text-end">
                                <div class="fs-6 opacity-75">{{ Carbon\Carbon::now()->format('l, d F Y') }}</div>
                                <div class="fs-5 fw-bold">{{ Carbon\Carbon::now()->format('H:i') }} WIB</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 1 -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Permohonan</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($totalRequests) }}</div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-calendar-day me-1"></i>{{ $todayStats['requests'] }} hari ini
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($pendingRequests) }}</div>
                            <div class="text-xs text-muted mt-1">
                                <span class="badge bg-warning bg-opacity-20 text-warning">{{ $statusStats['pending']['percentage'] }}%</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Disetujui</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($approvedRequests) }}</div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-check-circle me-1"></i>{{ $todayStats['approved'] }} hari ini
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-success bg-opacity-10 text-success rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">LOA Tervalidasi</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($validatedLoas) }}</div>
                            <div class="text-xs text-muted mt-1">
                                <span class="badge bg-info bg-opacity-20 text-info">Aktif</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-info bg-opacity-10 text-info rounded-circle p-3">
                                <i class="fas fa-certificate fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-secondary text-uppercase mb-1">Total Jurnal</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($totalJournals) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-secondary bg-opacity-10 text-secondary rounded-circle p-3">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-dark text-uppercase mb-1">Total Penerbit</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($totalPublishers) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-dark bg-opacity-10 text-dark rounded-circle p-3">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">Ditolak</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($rejectedRequests) }}</div>
                            <div class="text-xs text-muted mt-1">
                                <span class="badge bg-danger bg-opacity-20 text-danger">{{ $statusStats['rejected']['percentage'] }}%</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-circle p-3">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row">
        <!-- Status Distribution Chart -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Distribusi Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <div class="text-warning fw-bold">{{ $statusStats['pending']['percentage'] }}%</div>
                            <div class="text-xs text-muted">Pending</div>
                        </div>
                        <div class="col-4">
                            <div class="text-success fw-bold">{{ $statusStats['approved']['percentage'] }}%</div>
                            <div class="text-xs text-muted">Disetujui</div>
                        </div>
                        <div class="col-4">
                            <div class="text-danger fw-bold">{{ $statusStats['rejected']['percentage'] }}%</div>
                            <div class="text-xs text-muted">Ditolak</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="col-xl-8 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Tren Permohonan Bulanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <!-- Recent Requests -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>Permohonan Terbaru
                    </h6>
                    <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-3">No. Registrasi</th>
                                    <th class="border-0">Judul Artikel</th>
                                    <th class="border-0">Jurnal</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests as $request)
                                <tr>
                                    <td class="ps-3">
                                        <code class="text-primary">{{ $request->no_reg }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($request->article_title, 40) }}</div>
                                        <small class="text-muted">{{ $request->author }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $request->journal->name }}</div>
                                        <small class="text-muted">{{ $request->journal->publisher->name }}</small>
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada permohonan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Journals -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-trophy me-2 text-primary"></i>Jurnal Terpopuler
                    </h6>
                    <a href="{{ route('admin.journals.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topJournals as $index => $journal)
                        <div class="list-group-item border-0 d-flex align-items-center">
                            <div class="me-3">
                                <div class="badge bg-primary rounded-circle p-2">{{ $index + 1 }}</div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ Str::limit($journal->name, 30) }}</div>
                                <small class="text-muted">{{ $journal->publisher->name }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $journal->loa_requests_count }}</div>
                                <small class="text-muted">permohonan</small>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item border-0 text-center py-4 text-muted">
                            <i class="fas fa-book fa-2x mb-2 d-block"></i>
                            Belum ada data
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-file-alt fa-2x mb-2"></i>
                                <span>Kelola Permohonan</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.journals.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <span>Kelola Jurnal</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.publishers.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <span>Kelola Penerbit</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('loa.search') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <span>Cari LOA</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    
    .icon-shape {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        vertical-align: middle;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    
    .card {
        transition: all 0.15s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .list-group-item {
        transition: all 0.15s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fc;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Disetujui', 'Ditolak'],
        datasets: [{
            data: [{{ $pendingRequests }}, {{ $approvedRequests }}, {{ $rejectedRequests }}],
            backgroundColor: ['#ffc107', '#198754', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Monthly Trend Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyStats, 'month')) !!},
        datasets: [{
            label: 'Total Permohonan',
            data: {!! json_encode(array_column($monthlyStats, 'requests')) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Disetujui',
            data: {!! json_encode(array_column($monthlyStats, 'approved')) !!},
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
