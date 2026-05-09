@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Publisher Dashboard</h2>
    <div class="text-muted">
        <i class="fas fa-calendar-alt me-1"></i>{{ now()->format('d F Y') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white border-0 stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['publishers'] ?? 0 }}</h4>
                        <small>Publisher Saya</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white border-0 stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['journals'] ?? 0 }}</h4>
                        <small>Jurnal Saya</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-dark border-0 stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['loa_requests']['pending'] ?? 0 }}</h4>
                        <small>LOA Pending</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white border-0 stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['loa_requests']['total'] ?? 0 }}</h4>
                        <small>Total LOA Requests</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LOA Status Overview -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-success stat-card">
            <div class="card-body text-center">
                <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                <h5 class="text-success">{{ $stats['loa_requests']['approved'] ?? 0 }}</h5>
                <small class="text-muted">Approved</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-danger stat-card">
            <div class="card-body text-center">
                <i class="fas fa-times-circle text-danger fa-2x mb-2"></i>
                <h5 class="text-danger">{{ $stats['loa_requests']['rejected'] ?? 0 }}</h5>
                <small class="text-muted">Rejected</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-primary stat-card">
            <div class="card-body text-center">
                <i class="fas fa-certificate text-primary fa-2x mb-2"></i>
                <h5 class="text-primary">{{ $stats['validated'] ?? 0 }}</h5>
                <small class="text-muted">Validated LOAs</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-secondary stat-card">
            <div class="card-body text-center">
                <i class="fas fa-percentage text-secondary fa-2x mb-2"></i>
                <h5 class="text-secondary">
                    @if($stats['loa_requests']['total'] > 0)
                        {{ round(($stats['loa_requests']['approved'] / $stats['loa_requests']['total']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </h5>
                <small class="text-muted">Approval Rate</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.publishers.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-building me-2"></i>Manage Publishers
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.journals.index') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-book me-2"></i>Manage Journals
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-file-alt me-2"></i>Review LOAs
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.loa-templates.index') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-file-code me-2"></i>LOA Templates
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('loa.validated') }}" class="btn btn-info btn-lg w-100" target="_blank">
                            <i class="fas fa-search me-2"></i>Search LOAs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Chart -->
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Tren LOA 6 Bulan Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="loaMonthlyChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2 text-success"></i>Distribusi Status</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="loaStatusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent LOA Requests -->
<div class="row">
    <div class="col-lg-12">
        <div class="card recent-requests-card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Recent LOA Requests</h5>
                <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Article Title</th>
                                    <th>Journal</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $request->article_title }}">
                                            {{ $request->article_title }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->journal->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $request->authors }}</td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($request->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $request->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('publisher.loa-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No LOA requests yet</h5>
                        <p class="text-muted">LOA requests for your journals will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Audit Trail -->
@php $recentActivity = $recentActivity ?? collect(); @endphp
<div class="row mt-2 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-history me-2 text-secondary"></i>Riwayat Aktivitas LOA</h5>
            </div>
            <div class="card-body p-0">
                @if($recentActivity->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Waktu</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivity as $log)
                            <tr>
                                <td class="ps-3 text-muted small" style="white-space:nowrap;">
                                    {{ $log->created_at->format('d M Y H:i') }}
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($log->action) {
                                            'approve_loa' => 'success',
                                            'reject_loa'  => 'danger',
                                            default       => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }} small">{{ str_replace('_', ' ', $log->action) }}</span>
                                </td>
                                <td class="small">{{ $log->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4 small">Belum ada aktivitas tercatat.</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const monthly = @json($monthlyStats ?? []);
const labels  = monthly.map(m => m.label);

// Bar chart — monthly trend
new Chart(document.getElementById('loaMonthlyChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            { label: 'Diajukan',  data: monthly.map(m => m.total),    backgroundColor: 'rgba(99,102,241,.7)',  borderRadius: 4 },
            { label: 'Disetujui', data: monthly.map(m => m.approved), backgroundColor: 'rgba(16,185,129,.7)', borderRadius: 4 },
            { label: 'Ditolak',   data: monthly.map(m => m.rejected), backgroundColor: 'rgba(239,68,68,.7)',  borderRadius: 4 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// Doughnut chart — status distribution
new Chart(document.getElementById('loaStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Disetujui', 'Ditolak'],
        datasets: [{ data: [{{ $stats['loa_requests']['pending'] }}, {{ $stats['loa_requests']['approved'] }}, {{ $stats['loa_requests']['rejected'] }}], backgroundColor: ['#F59E0B','#10B981','#EF4444'], borderWidth: 2 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});
</script>
@endpush
