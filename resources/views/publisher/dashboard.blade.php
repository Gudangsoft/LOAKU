@extends('publisher.layout')

@push('styles')
<style>
.stat-card { border-radius: 14px; }
.stat-icon { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
.urgent-row { animation: pulse-bg 2s ease-in-out infinite; }
@keyframes pulse-bg { 0%,100% { background:#fff8e1; } 50% { background:#fff3cd; } }
.completeness-bar { height:8px; border-radius:4px; background:#e9ecef; }
.completeness-fill { height:8px; border-radius:4px; transition:width .6s ease; }
.quick-search-result { position:absolute; z-index:999; width:100%; background:#fff; border:1px solid #ddd; border-radius:8px; max-height:260px; overflow-y:auto; box-shadow:0 4px 16px rgba(0,0,0,.12); }
.search-item { padding:.5rem .75rem; cursor:pointer; border-bottom:1px solid #f0f0f0; }
.search-item:hover { background:#f8f9fa; }
.widget-embed-box { background:#1e3c72; color:#fff; border-radius:12px; padding:1.25rem; font-family:monospace; font-size:.78rem; word-break:break-all; }
.sub-card { border-left:4px solid #0d6efd; border-radius:10px; }
.sub-expired { border-left-color:#dc3545 !important; }
.sub-expiring { border-left-color:#fd7e14 !important; }
</style>
@endpush

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Publisher Dashboard</h2>
    <div class="d-flex gap-2 align-items-center">
        <span class="text-muted small"><i class="fas fa-calendar-alt me-1"></i>{{ now()->format('d F Y') }}</span>
        <a href="{{ route('publisher.dashboard.export') }}" class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-csv me-1"></i>Export CSV
        </a>
    </div>
</div>

{{-- ① PANEL LOA MENDESAK --}}
@if(($stats['urgent_count'] ?? 0) > 0)
<div class="alert alert-warning d-flex align-items-start gap-3 mb-4 rounded-3 shadow-sm" role="alert">
    <i class="fas fa-exclamation-triangle fa-lg mt-1 text-warning"></i>
    <div class="flex-grow-1">
        <strong>{{ $stats['urgent_count'] }} LOA Mendesak!</strong>
        <span class="ms-1 text-muted small">Sudah pending lebih dari 3 hari — mohon segera ditindaklanjuti.</span>
        <div class="mt-2 d-flex flex-wrap gap-2">
            @foreach($urgentLoaRequests->take(5) as $ul)
            <span class="badge bg-warning text-dark">
                {{ \Illuminate\Support\Str::limit($ul->article_title, 28) }}
                <small class="ms-1 opacity-75">({{ $ul->created_at->diffForHumans() }})</small>
            </span>
            @endforeach
        </div>
    </div>
    <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-warning btn-sm flex-shrink-0">Tinjau</a>
</div>
@endif

{{-- ② QUICK SEARCH --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <div class="position-relative">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="loaQuickSearch" class="form-control border-start-0 ps-0" placeholder="Cari LOA — judul artikel, nama penulis, atau kode LOA..." autocomplete="off">
                <span class="input-group-text bg-white" id="searchSpinner" style="display:none;">
                    <span class="spinner-border spinner-border-sm text-primary"></span>
                </span>
            </div>
            <div id="searchResults" class="quick-search-result" style="display:none;"></div>
        </div>
    </div>
</div>

{{-- STAT CARDS ROW --}}
<div class="row mb-4 g-3">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-primary bg-opacity-10 mb-2"><i class="fas fa-building text-primary"></i></div>
                <h4 class="mb-0 fw-bold">{{ $stats['publishers'] ?? 0 }}</h4>
                <small class="text-muted">Publisher</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-success bg-opacity-10 mb-2"><i class="fas fa-book text-success"></i></div>
                <h4 class="mb-0 fw-bold">{{ $stats['journals'] ?? 0 }}</h4>
                <small class="text-muted">Jurnal</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-warning bg-opacity-10 mb-2"><i class="fas fa-hourglass-half text-warning"></i></div>
                <h4 class="mb-0 fw-bold">{{ $stats['loa_requests']['pending'] ?? 0 }}</h4>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-danger bg-opacity-10 mb-2"><i class="fas fa-times-circle text-danger"></i></div>
                <h4 class="mb-0 fw-bold">{{ $stats['loa_requests']['rejected'] ?? 0 }}</h4>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-info bg-opacity-10 mb-2"><i class="fas fa-certificate text-info"></i></div>
                <h4 class="mb-0 fw-bold">{{ $stats['validated'] ?? 0 }}</h4>
                <small class="text-muted">Tervalidasi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-secondary bg-opacity-10 mb-2"><i class="fas fa-clock text-secondary"></i></div>
                <h4 class="mb-0 fw-bold">
                    @if($stats['avg_response_hours'])
                        {{ $stats['avg_response_hours'] < 24 ? $stats['avg_response_hours'].'j' : round($stats['avg_response_hours']/24, 1).'h' }}
                    @else
                        -
                    @endif
                </h4>
                <small class="text-muted">Rata-rata Respon</small>
            </div>
        </div>
    </div>
</div>

{{-- SUBSCRIPTION & WIDGET ROW --}}
<div class="row mb-4 g-3">

    {{-- ⑥ STATUS LANGGANAN --}}
    <div class="col-lg-4">
        @php
            $daysLeft = $activeSub?->daysRemaining() ?? 0;
            $subClass = '';
            if (!$activeSub) $subClass = 'sub-expired';
            elseif ($daysLeft <= 7) $subClass = 'sub-expiring';
        @endphp
        <div class="card border-0 shadow-sm sub-card {{ $subClass }} h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="fas fa-box-open me-2"></i>Status Langganan</h6>
                @if($activeSub && $subPlan)
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold fs-5">{{ $subPlan->name }}</span>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                    <div class="text-muted small mb-2">
                        Berlaku hingga: <strong>{{ $activeSub->end_date->format('d M Y') }}</strong>
                    </div>
                    <div class="text-muted small mb-3">
                        Sisa: <strong class="{{ $daysLeft <= 7 ? 'text-danger' : 'text-success' }}">{{ $daysLeft }} hari</strong>
                        &nbsp;|&nbsp; LOA/bln: <strong>{{ $subPlan->maxLoaPerMonthLabel() }}</strong>
                        &nbsp;|&nbsp; Jurnal maks: <strong>{{ $subPlan->max_journals ?? '∞' }}</strong>
                    </div>
                    @if($subPlan->max_loa_per_month)
                    <div class="mb-1 d-flex justify-content-between small">
                        <span>Penggunaan bulan ini</span>
                        <span>{{ $thisMonthLoa }} / {{ $subPlan->max_loa_per_month }}</span>
                    </div>
                    <div class="completeness-bar">
                        <div class="completeness-fill bg-primary" style="width:{{ min(100, round(($thisMonthLoa/$subPlan->max_loa_per_month)*100)) }}%"></div>
                    </div>
                    @endif
                    @if($daysLeft <= 7)
                    <a href="{{ route('publisher.subscription.index') }}" class="btn btn-warning btn-sm mt-3 w-100">
                        <i class="fas fa-redo me-1"></i>Perpanjang Sekarang
                    </a>
                    @endif
                @else
                    <div class="text-center py-2">
                        <i class="fas fa-ban fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-2">Tidak ada langganan aktif</p>
                        <a href="{{ route('publisher.subscription.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-shopping-cart me-1"></i>Berlangganan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ⑩ WIDGET EMBED --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="fas fa-code me-2"></i>Widget Statistik LOA</h6>
                <p class="small text-muted mb-2">
                    Embed badge jumlah LOA tervalidasi ke website jurnal Anda.
                    <strong>{{ $widgetCount }}</strong> LOA tervalidasi siap ditampilkan.
                </p>
                @php
                    $widgetUrl = request()->getSchemeAndHttpHost() . '/api/widget/loa-count/' . (Auth::id());
                    $embedCode = '<a href="'.url('/loa/validated').'"><img src="'.$widgetUrl.'" alt="LOA Validated"></a>';
                @endphp
                <div class="widget-embed-box mb-2">{{ $embedCode }}</div>
                <button class="btn btn-sm btn-outline-light w-100 bg-secondary text-white" onclick="copyWidget()" style="border-color:rgba(255,255,255,.3)">
                    <i class="fas fa-copy me-1"></i>Salin Kode
                </button>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-warning btn-sm text-start">
                        <i class="fas fa-file-alt me-2"></i>Review LOA
                        @if(($stats['loa_requests']['pending']??0)>0)
                            <span class="badge bg-dark ms-1">{{ $stats['loa_requests']['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('publisher.journals.index') }}" class="btn btn-success btn-sm text-start">
                        <i class="fas fa-book me-2"></i>Kelola Jurnal
                    </a>
                    <a href="{{ route('publisher.loa-templates.index') }}" class="btn btn-info btn-sm text-start">
                        <i class="fas fa-file-code me-2"></i>Template LOA
                    </a>
                    <a href="{{ route('publisher.publishers.index') }}" class="btn btn-primary btn-sm text-start">
                        <i class="fas fa-building me-2"></i>Kelola Publisher
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="row mb-4 g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Tren LOA 6 Bulan Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="loaMonthlyChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>LOA per Jurnal</h6>
            </div>
            <div class="card-body">
                <canvas id="journalChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ⑤ KELENGKAPAN JURNAL + ④ STATS PER JURNAL --}}
@if($journalStats->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-tasks me-2 text-primary"></i>Kelengkapan & Statistik per Jurnal</h6>
        <a href="{{ route('publisher.journals.index') }}" class="btn btn-sm btn-outline-primary">Kelola Jurnal</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Jurnal</th>
                    <th class="text-center">Total LOA</th>
                    <th class="text-center">Pending</th>
                    <th class="text-center">Approved</th>
                    <th class="text-center">Validated</th>
                    <th style="min-width:160px;">Kelengkapan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($journalStats as $js)
                @php
                    $pct = $js['completeness'];
                    $barColor = $pct >= 80 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger');
                @endphp
                <tr>
                    <td class="ps-3">
                        <div class="fw-semibold text-truncate" style="max-width:200px;">{{ $js['journal']->name }}</div>
                        @if($js['journal']->e_issn || $js['journal']->p_issn)
                        <small class="text-muted">{{ $js['journal']->e_issn ?? $js['journal']->p_issn }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $js['total'] }}</td>
                    <td class="text-center">
                        @if($js['pending'] > 0)
                        <span class="badge bg-warning text-dark">{{ $js['pending'] }}</span>
                        @else
                        <span class="text-muted">0</span>
                        @endif
                    </td>
                    <td class="text-center"><span class="text-success fw-semibold">{{ $js['approved'] }}</span></td>
                    <td class="text-center"><span class="text-primary fw-semibold">{{ $js['validated'] }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="completeness-bar flex-grow-1">
                                <div class="completeness-fill {{ $barColor }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <small class="text-muted flex-shrink-0">{{ $js['filled'] }}/{{ $js['total_fields'] }}</small>
                        </div>
                        @if($pct < 100)
                        <div style="font-size:.7rem;" class="text-muted mt-1">
                            @foreach(['name','e_issn','p_issn','chief_editor','email','logo','signature_stamp','sinta_id'] as $fld)
                                @if(empty($js['journal']->$fld))
                                <span class="badge bg-light text-danger border border-danger me-1" title="{{ $fld }} belum diisi">{{ $fld }}</span>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('publisher.journals.edit', $js['journal']) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ② RECENT LOA DENGAN QUICK APPROVE/REJECT --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>LOA Requests Terbaru</h6>
        <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        @if($recentRequests->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Judul Artikel</th>
                        <th>Jurnal</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequests as $req)
                    <tr class="{{ $req->status === 'pending' && $req->created_at->diffInDays() >= 3 ? 'urgent-row' : '' }}">
                        <td class="ps-3">
                            <div class="text-truncate fw-semibold" style="max-width:230px;" title="{{ $req->article_title }}">
                                {{ $req->article_title }}
                            </div>
                        </td>
                        <td><small class="text-muted">{{ $req->journal?->name ?? 'N/A' }}</small></td>
                        <td><small>{{ \Illuminate\Support\Str::limit($req->author, 30) }}</small></td>
                        <td>
                            @if($req->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $req->created_at->format('d/m/Y') }}</small></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('publisher.loa-requests.show', $req) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($req->status === 'pending')
                                <form method="POST" action="{{ route('publisher.loa-requests.approve', $req) }}" class="d-inline"
                                      onsubmit="return confirm('Setujui LOA ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" title="Tolak"
                                    onclick="showRejectModal({{ $req->id }}, '{{ addslashes($req->article_title) }}')">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada LOA request.</p>
        </div>
        @endif
    </div>
</div>

{{-- ACTIVITY LOG --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-history me-2 text-secondary"></i>Riwayat Aktivitas</h6>
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
                        <td class="ps-3 text-muted small" style="white-space:nowrap;">{{ $log->created_at->format('d M Y H:i') }}</td>
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

{{-- REJECT MODAL --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-times-circle text-danger me-2"></i>Tolak LOA Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3" id="rejectArticleTitle"></p>
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="4" required
                              placeholder="Tuliskan alasan penolakan yang jelas..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times me-1"></i>Tolak LOA</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Charts ────────────────────────────────────────────────
const monthly = @json($monthlyStats ?? []);
const labels  = monthly.map(m => m.label);

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

const jcd = @json($journalChartData ?? []);
if (jcd.length) {
    new Chart(document.getElementById('journalChart'), {
        type: 'bar',
        data: {
            labels: jcd.map(j => j.label),
            datasets: [
                { label: 'Approved', data: jcd.map(j => j.approved), backgroundColor: 'rgba(16,185,129,.7)', borderRadius: 4 },
                { label: 'Pending',  data: jcd.map(j => j.pending),  backgroundColor: 'rgba(245,158,11,.7)', borderRadius: 4 },
            ]
        },
        options: { responsive: true, indexAxis: 'y', plugins: { legend: { position: 'bottom' } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
}

// ── Quick Search ───────────────────────────────────────────
let searchTimeout;
const searchInput   = document.getElementById('loaQuickSearch');
const searchResults = document.getElementById('searchResults');
const searchSpinner = document.getElementById('searchSpinner');

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) { searchResults.style.display = 'none'; return; }
    searchTimeout = setTimeout(() => {
        searchSpinner.style.display = 'flex';
        fetch(`{{ route('publisher.loa-requests.index') }}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            searchSpinner.style.display = 'none';
            if (!data || !data.data || data.data.length === 0) {
                searchResults.innerHTML = '<div class="search-item text-muted">Tidak ada hasil ditemukan</div>';
            } else {
                searchResults.innerHTML = data.data.slice(0,8).map(r => `
                    <a href="{{ url('publisher/loa-requests') }}/${r.id}" class="d-block text-decoration-none text-dark search-item">
                        <div class="fw-semibold small">${r.article_title}</div>
                        <div class="text-muted" style="font-size:.75rem;">${r.author ?? ''} &middot; <span class="badge bg-secondary">${r.status}</span></div>
                    </a>`).join('');
            }
            searchResults.style.display = 'block';
        })
        .catch(() => { searchSpinner.style.display = 'none'; });
    }, 350);
});

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// ── Reject Modal ───────────────────────────────────────────
function showRejectModal(id, title) {
    document.getElementById('rejectForm').action = `/publisher/loa-requests/${id}/reject`;
    document.getElementById('rejectArticleTitle').textContent = `Artikel: ${title}`;
    document.querySelector('#rejectModal textarea').value = '';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

// ── Copy Widget ────────────────────────────────────────────
function copyWidget() {
    const code = @json($embedCode ?? '');
    navigator.clipboard.writeText(code).then(() => {
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Kode disalin!', timer:2000, showConfirmButton:false });
    });
}
</script>
@endpush
