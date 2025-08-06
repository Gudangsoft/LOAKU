@extends('admin.layouts.modern-app')

@section('title', 'Data LOA Requests')

@section('breadcrumb')
    <li class="breadcrumb-item active">LOA Requests</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Manajemen</div>
                <h2 class="page-title">Permintaan LOA</h2>
                <p class="page-subtitle">Kelola semua permintaan Letter of Acceptance dari peneliti</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.loa-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah LOA Request
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>
                            Export
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-file-excel me-2 text-success"></i>Excel
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-file-pdf me-2 text-danger"></i>PDF
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-file-csv me-2 text-info"></i>CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stats-card-mini">
                <div class="stats-icon-mini bg-primary">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stats-content-mini">
                    <div class="stats-number">{{ $totalRequests ?? 156 }}</div>
                    <div class="stats-label">Total Requests</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stats-card-mini">
                <div class="stats-icon-mini bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-content-mini">
                    <div class="stats-number">{{ $pendingRequests ?? 23 }}</div>
                    <div class="stats-label">Pending Review</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stats-card-mini">
                <div class="stats-icon-mini bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content-mini">
                    <div class="stats-number">{{ $approvedRequests ?? 118 }}</div>
                    <div class="stats-label">Approved</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stats-card-mini">
                <div class="stats-icon-mini bg-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stats-content-mini">
                    <div class="stats-number">{{ $rejectedRequests ?? 15 }}</div>
                    <div class="stats-label">Rejected</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari berdasarkan judul, author, atau email..." id="searchInput">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Jurnal</label>
                        <select class="form-select" id="journalFilter">
                            <option value="">Semua Jurnal</option>
                            @foreach($journals ?? [] as $journal)
                            <option value="{{ $journal->id }}">{{ $journal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                Daftar Permintaan LOA
            </h5>
            <div class="card-actions">
                <div class="dropdown">
                    <button class="btn btn-ghost-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#" onclick="refreshTable()">
                            <i class="fas fa-sync me-2"></i>Refresh
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-columns me-2"></i>Columns
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-print me-2"></i>Print
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="loaRequestsTable">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>Article Info</th>
                        <th>Author</th>
                        <th>Journal</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loaRequests ?? [] as $request)
                    <tr class="table-row" data-status="{{ $request->status }}" data-journal="{{ $request->journal_id ?? '' }}">
                        <td>
                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $request->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="article-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="article-title">{{ Str::limit($request->article_title ?? 'Untitled Article', 60) }}</div>
                                    <div class="article-meta">
                                        Vol. {{ $request->volume ?? 'N/A' }}, No. {{ $request->number ?? 'N/A' }} 
                                        ({{ $request->year ?? 'N/A' }})
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="author-info">
                                <div class="author-name">{{ $request->author ?? 'Unknown' }}</div>
                                <div class="author-email">{{ $request->author_email ?? 'No email' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="journal-info">
                                <div class="journal-name">{{ $request->journal->name ?? 'No Journal' }}</div>
                                <div class="journal-issn">{{ $request->journal->e_issn ?? 'No ISSN' }}</div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $status = $request->status ?? 'pending';
                            @endphp
                            <span class="badge bg-{{ $statusClass[$status] ?? 'secondary' }} status-badge">
                                <i class="fas fa-{{ $status == 'approved' ? 'check' : ($status == 'rejected' ? 'times' : 'clock') }} me-1"></i>
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date">{{ $request->created_at ? $request->created_at->format('d M Y') : 'Unknown' }}</div>
                                <div class="time">{{ $request->created_at ? $request->created_at->format('H:i') : '' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.loa-requests.show', $request) }}" 
                                   class="btn btn-ghost-primary btn-sm" 
                                   data-bs-toggle="tooltip" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($request->status == 'pending')
                                <div class="dropdown">
                                    <button class="btn btn-ghost-secondary btn-sm dropdown-toggle" 
                                            data-bs-toggle="dropdown" 
                                            data-bs-toggle="tooltip" 
                                            title="Actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <form method="POST" action="{{ route('admin.loa-requests.approve', $request) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success" 
                                                    onclick="return confirm('Approve this LOA request?')">
                                                <i class="fas fa-check me-2"></i>Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.loa-requests.reject', $request) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger" 
                                                    onclick="return confirm('Reject this LOA request?')">
                                                <i class="fas fa-times me-2"></i>Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">No LOA Requests Found</h5>
                                <p class="text-muted">There are no LOA requests to display at the moment.</p>
                                <a href="{{ route('admin.loa-requests.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create First Request
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($loaRequests) && $loaRequests->hasPages())
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col">
                    <div class="text-muted">
                        Showing {{ $loaRequests->firstItem() }} to {{ $loaRequests->lastItem() }} of {{ $loaRequests->total() }} entries
                    </div>
                </div>
                <div class="col-auto">
                    {{ $loaRequests->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Bulk Actions (Hidden by default) -->
    <div class="bulk-actions" id="bulkActions" style="display: none;">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <strong><span id="selectedCount">0</span> items selected</strong>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm" onclick="bulkApprove()">
                                <i class="fas fa-check me-2"></i>Approve Selected
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="bulkReject()">
                                <i class="fas fa-times me-2"></i>Reject Selected
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                                <i class="fas fa-times me-2"></i>Clear Selection
                            </button>
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
    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-pretitle {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* Mini Stats Cards */
    .stats-card-mini {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stats-card-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .stats-icon-mini {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stats-content-mini {
        flex: 1;
    }

    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark-color);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stats-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table th {
        border-top: none;
        border-bottom: 2px solid #f1f5f9;
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 0.75rem;
    }

    .table td {
        border-top: 1px solid #f1f5f9;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.02);
    }

    /* Table Content Styles */
    .article-icon {
        width: 40px;
        height: 40px;
        background: rgba(79, 70, 229, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .article-title {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 0.25rem;
    }

    .article-meta {
        color: #6b7280;
        font-size: 0.8rem;
    }

    .author-name {
        font-weight: 500;
        color: var(--dark-color);
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .author-email {
        color: #6b7280;
        font-size: 0.8rem;
    }

    .journal-name {
        font-weight: 500;
        color: var(--dark-color);
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .journal-issn {
        color: #6b7280;
        font-size: 0.8rem;
        font-family: 'Courier New', monospace;
    }

    .status-badge {
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
    }

    .date-info .date {
        font-weight: 500;
        color: var(--dark-color);
        font-size: 0.9rem;
    }

    .date-info .time {
        color: #6b7280;
        font-size: 0.8rem;
    }

    /* Button Styles */
    .btn-ghost-primary {
        color: var(--primary-color);
        border: 1px solid transparent;
        background: rgba(79, 70, 229, 0.1);
    }

    .btn-ghost-primary:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-ghost-secondary {
        color: #6b7280;
        border: 1px solid transparent;
        background: #f8fafc;
    }

    .btn-ghost-secondary:hover {
        background: #e5e7eb;
        color: var(--dark-color);
    }

    /* Card Actions */
    .card-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Bulk Actions */
    .bulk-actions {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        min-width: 400px;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateX(-50%) translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .stats-card-mini {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .bulk-actions {
            left: 1rem;
            right: 1rem;
            transform: none;
            min-width: auto;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        if (selected.length > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = selected.length;
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function clearSelection() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        updateBulkActions();
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const rows = document.querySelectorAll('.table-row');

        rows.forEach(row => {
            if (!selectedStatus || row.dataset.status === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Journal filter
    document.getElementById('journalFilter').addEventListener('change', function() {
        const selectedJournal = this.value;
        const rows = document.querySelectorAll('.table-row');

        rows.forEach(row => {
            if (!selectedJournal || row.dataset.journal === selectedJournal) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('journalFilter').value = '';
        
        document.querySelectorAll('.table-row').forEach(row => {
            row.style.display = '';
        });
    }

    function refreshTable() {
        window.location.reload();
    }

    function bulkApprove() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) return;

        if (confirm(`Approve ${selected.length} selected requests?`)) {
            // Implement bulk approve logic here
            console.log('Bulk approve:', Array.from(selected).map(cb => cb.value));
        }
    }

    function bulkReject() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) return;

        if (confirm(`Reject ${selected.length} selected requests?`)) {
            // Implement bulk reject logic here
            console.log('Bulk reject:', Array.from(selected).map(cb => cb.value));
        }
    }
</script>
@endsection
