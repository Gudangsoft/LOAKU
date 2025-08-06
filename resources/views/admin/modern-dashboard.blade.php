@extends('admin.layouts.modern-app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="welcome-content">
                            <h1 class="welcome-title">
                                Selamat Datang, {{ Auth::user()->name }}! 👋
                            </h1>
                            <p class="welcome-subtitle">
                                @if(Auth::user()->isAdministrator())
                                    Anda masuk sebagai <strong>Administrator</strong>. Anda memiliki akses penuh ke sistem manajemen LOA.
                                @else
                                    Anda masuk sebagai <strong>Member</strong>. Anda dapat mengelola permintaan LOA dan melihat data jurnal.
                                @endif
                            </p>
                            <div class="welcome-actions mt-3">
                                <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-primary me-3">
                                    <i class="fas fa-plus me-2"></i>Kelola LOA
                                </a>
                                <a href="{{ route('loa.validated') }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-search me-2"></i>Cari LOA
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end d-none d-lg-block">
                        <div class="welcome-illustration">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <h3 class="stats-value">{{ $totalRequests ?? 0 }}</h3>
                <p class="stats-label">Total Permintaan LOA</p>
                <div class="stats-trend">
                    <i class="fas fa-arrow-up trend-up"></i>
                    <span class="text-success">+12% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card success">
                <div class="stats-header">
                    <div class="stats-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <h3 class="stats-value">{{ $approvedRequests ?? 0 }}</h3>
                <p class="stats-label">LOA Disetujui</p>
                <div class="stats-trend">
                    <i class="fas fa-arrow-up trend-up"></i>
                    <span class="text-success">+8% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card warning">
                <div class="stats-header">
                    <div class="stats-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <h3 class="stats-value">{{ $pendingRequests ?? 0 }}</h3>
                <p class="stats-label">Menunggu Review</p>
                <div class="stats-trend">
                    <i class="fas fa-arrow-down trend-down"></i>
                    <span class="text-danger">-3% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card info">
                <div class="stats-header">
                    <div class="stats-icon info">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
                <h3 class="stats-value">{{ $totalJournals ?? 0 }}</h3>
                <p class="stats-label">Total Jurnal</p>
                <div class="stats-trend">
                    <i class="fas fa-arrow-up trend-up"></i>
                    <span class="text-success">+5% dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area me-2 text-primary"></i>
                        Statistik Permintaan LOA (6 Bulan Terakhir)
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            6 Bulan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">3 Bulan</a></li>
                            <li><a class="dropdown-item" href="#">6 Bulan</a></li>
                            <li><a class="dropdown-item" href="#">1 Tahun</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="requestsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-success"></i>
                        Status LOA
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2 text-info"></i>
                        Aktivitas Terbaru
                    </h5>
                    <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="activity-list">
                        @forelse($recentRequests ?? [] as $request)
                        <div class="activity-item">
                            <div class="activity-icon bg-primary">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-header">
                                    <h6 class="activity-title">{{ $request->article_title ?? 'Artikel Baru' }}</h6>
                                    <span class="activity-time">{{ $request->created_at ? $request->created_at->diffForHumans() : 'Baru saja' }}</span>
                                </div>
                                <p class="activity-description">
                                    Permintaan LOA dari <strong>{{ $request->author ?? 'Penulis' }}</strong>
                                    @if($request->status == 'pending')
                                        <span class="badge bg-warning ms-2">Menunggu Review</span>
                                    @elseif($request->status == 'approved')
                                        <span class="badge bg-success ms-2">Disetujui</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger ms-2">Ditolak</span>
                                    @endif
                                </p>
                            </div>
                            <div class="activity-actions">
                                <a href="{{ route('admin.loa-requests.show', $request) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted">Belum ada aktivitas terbaru</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.loa-requests.index') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Kelola Permintaan LOA</h6>
                                <p>Review dan setujui permintaan LOA</p>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="{{ route('admin.journals.create') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Tambah Jurnal Baru</h6>
                                <p>Daftarkan jurnal baru ke sistem</p>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="{{ route('admin.publishers.create') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-info">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Tambah Penerbit</h6>
                                <p>Daftarkan penerbit baru</p>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        @if(Auth::user()->isAdministrator())
                        <a href="{{ route('admin.users.create') }}" class="quick-action-item">
                            <div class="quick-action-icon bg-warning">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Tambah User</h6>
                                <p>Buat akun admin/member baru</p>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="system-info">
                        <div class="info-item">
                            <span class="info-label">Versi Sistem:</span>
                            <span class="info-value">v2.0.0</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Update:</span>
                            <span class="info-value">{{ now()->format('d M Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status Server:</span>
                            <span class="info-value">
                                <span class="badge bg-success">Online</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .welcome-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
        line-height: 1.6;
    }

    .welcome-illustration {
        font-size: 6rem;
        opacity: 0.3;
        position: relative;
        z-index: 1;
    }

    /* Activity List */
    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: #f8fafc;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .activity-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        color: var(--dark-color);
    }

    .activity-time {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .activity-description {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    .activity-actions {
        margin-left: 1rem;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .quick-action-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
    }

    .quick-action-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: inherit;
        text-decoration: none;
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
    }

    .quick-action-content {
        flex: 1;
    }

    .quick-action-content h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        color: var(--dark-color);
    }

    .quick-action-content p {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
    }

    /* System Info */
    .system-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .info-value {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--dark-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 2rem;
        }
        
        .welcome-subtitle {
            font-size: 1rem;
        }
        
        .activity-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
        
        .quick-action-item {
            padding: 0.75rem;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Requests Chart
    const requestsCtx = document.getElementById('requestsChart').getContext('2d');
    new Chart(requestsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Permintaan LOA',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'LOA Disetujui',
                data: [8, 15, 12, 20, 18, 25],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Disetujui', 'Menunggu', 'Ditolak'],
            datasets: [{
                data: [{{ $approvedRequests ?? 65 }}, {{ $pendingRequests ?? 25 }}, {{ $rejectedRequests ?? 10 }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
</script>
@endsection
