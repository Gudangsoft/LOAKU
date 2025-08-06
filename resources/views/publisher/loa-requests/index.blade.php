@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>LOA Request Management</h2>
    <div class="d-flex gap-2">
        <select class="form-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-dark border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $requests->where('status', 'pending')->count() }}</h4>
                        <small>Pending Review</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $requests->where('status', 'approved')->count() }}</h4>
                        <small>Approved</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $requests->where('status', 'rejected')->count() }}</h4>
                        <small>Rejected</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $requests->total() }}</h4>
                        <small>Total Requests</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">LOA Requests for My Journals</h5>
        <div class="text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Manage LOA requests submitted to journals you publish
        </div>
    </div>
    <div class="card-body p-0">
        @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Request Info</th>
                            <th>Article Details</th>
                            <th>Journal</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                        <tr>
                            <td>
                                <div>
                                    <small class="text-muted">Reg: {{ $request->registration_number }}</small><br>
                                    <small class="text-primary">ID: {{ $request->article_id ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="text-truncate d-block" style="max-width: 250px;" title="{{ $request->article_title }}">
                                        {{ $request->article_title }}
                                    </strong>
                                    <small class="text-muted">
                                        Type: {{ ucfirst($request->article_type ?? 'N/A') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $request->journal->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $request->journal->publisher->name ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    {{ $request->authors }}<br>
                                    <small class="text-muted">{{ $request->corresponding_email }}</small>
                                </div>
                            </td>
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
                                <div>
                                    <small>Submitted: {{ $request->created_at->format('d/m/Y') }}</small><br>
                                    @if($request->approved_at)
                                        <small class="text-success">Approved: {{ $request->approved_at->format('d/m/Y') }}</small>
                                    @elseif($request->rejected_at)
                                        <small class="text-danger">Rejected: {{ $request->rejected_at->format('d/m/Y') }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('publisher.loa-requests.show', $request) }}" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($request->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="approveRequest({{ $request->id }})" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="rejectRequest({{ $request->id }})" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    @if($request->status === 'approved' && $request->loaValidated)
                                        <a href="{{ route('loa.print', $request->loaValidated->loa_code) }}" 
                                           class="btn btn-sm btn-info" target="_blank" title="Download LOA">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-3">
                {{ $requests->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No LOA Requests</h5>
                <p class="text-muted">LOA requests for your journals will appear here.</p>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject LOA Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required 
                                  placeholder="Please provide a clear reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function approveRequest(requestId) {
        Swal.fire({
            title: 'Approve LOA Request?',
            text: 'This will approve the LOA request and generate an LOA certificate.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/publisher/loa-requests/${requestId}/approve`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    function rejectRequest(requestId) {
        const rejectForm = document.getElementById('rejectForm');
        rejectForm.action = `/publisher/loa-requests/${requestId}/reject`;
        
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
    
    // Filter functionality
    document.getElementById('statusFilter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (selectedStatus === '') {
                row.style.display = '';
            } else {
                const statusBadge = row.querySelector('.badge');
                const rowStatus = statusBadge ? statusBadge.textContent.toLowerCase() : '';
                row.style.display = rowStatus === selectedStatus ? '' : 'none';
            }
        });
    });
</script>
@endsection
