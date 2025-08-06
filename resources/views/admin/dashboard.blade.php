@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of your LOA Management System')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalRequests ?? 0 }}</h3>
                <p>Total LOA Requests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $approvedRequests ?? 0 }}</h3>
                <p>Approved Requests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $pendingRequests ?? 0 }}</h3>
                <p>Pending Review</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $rejectedRequests ?? 0 }}</h3>
                <p>Rejected Requests</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-primary w-100">
                    <i class="fas fa-eye me-2"></i>Review LOA Requests
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.publishers.create') }}" class="btn btn-success w-100">
                    <i class="fas fa-plus me-2"></i>Add New Publisher
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.journals.create') }}" class="btn btn-info w-100">
                    <i class="fas fa-plus me-2"></i>Add New Journal
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Recent LOA Requests -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Recent LOA Requests</h3>
                    <a href="{{ route('admin.loa-requests.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                
                @if(isset($recentRequests) && $recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Article Title</th>
                                    <th>Author</th>
                                    <th>Journal</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($request->article_title, 40) }}</div>
                                    </td>
                                    <td>{{ $request->author }}</td>
                                    <td>
                                        <small class="text-muted">{{ $request->journal->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($request->status) {
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                                'pending' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->created_at->format('d M Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                        <h5>No LOA requests yet</h5>
                        <p>LOA requests will appear here once users start submitting them.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- System Info -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">System Information</h3>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-building me-2 text-primary"></i>Publishers</span>
                        <span class="badge bg-primary rounded-pill">{{ $totalPublishers ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-book me-2 text-success"></i>Journals</span>
                        <span class="badge bg-success rounded-pill">{{ $totalJournals ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-file-contract me-2 text-info"></i>Templates</span>
                        <span class="badge bg-info rounded-pill">{{ $totalTemplates ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users me-2 text-warning"></i>Admin Users</span>
                        <span class="badge bg-warning rounded-pill">{{ $adminUsers ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Activity</h3>
                </div>
                @if(isset($recentActivity) && count($recentActivity) > 0)
                    <div class="timeline">
                        @foreach($recentActivity as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>{{ $activity['title'] }}</h6>
                                <p class="mb-1">{{ $activity['description'] }}</p>
                                <small class="text-muted">{{ $activity['time'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-clock fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-color);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 0 0 3px var(--border-color);
    }

    .timeline-content h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .timeline-content p {
        font-size: 0.875rem;
        color: #64748b;
    }
</style>
@endpush
