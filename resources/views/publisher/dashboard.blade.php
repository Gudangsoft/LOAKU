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
                        <a href="{{ route('publisher.publishers') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-building me-2"></i>Manage Publishers
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.journals') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-book me-2"></i>Manage Journals
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.loa-requests') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-file-alt me-2"></i>Review LOAs
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('publisher.loa-templates') }}" class="btn btn-info btn-lg w-100">
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

<!-- Recent LOA Requests -->
<div class="row">
    <div class="col-lg-12">
        <div class="card recent-requests-card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Recent LOA Requests</h5>
                <a href="{{ route('publisher.loa-requests') }}" class="btn btn-sm btn-outline-primary">View All</a>
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
@endsection
