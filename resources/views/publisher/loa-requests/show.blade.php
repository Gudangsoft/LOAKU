@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>LOA Request Details</h2>
    <a href="{{ route('publisher.loa-requests') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Requests
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Request Information</h5>
                @if($loaRequest->status === 'pending')
                    <span class="badge bg-warning">Pending Review</span>
                @elseif($loaRequest->status === 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($loaRequest->status === 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Registration Number:</strong><br>
                        <span class="text-primary">{{ $loaRequest->registration_number }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Article ID:</strong><br>
                        {{ $loaRequest->article_id ?? 'N/A' }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Article Title:</strong><br>
                    {{ $loaRequest->article_title }}
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Authors:</strong><br>
                        {{ $loaRequest->authors }}
                    </div>
                    <div class="col-md-6">
                        <strong>Article Type:</strong><br>
                        {{ ucfirst($loaRequest->article_type ?? 'N/A') }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Corresponding Author:</strong><br>
                        {{ $loaRequest->corresponding_author }}
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $loaRequest->corresponding_email }}">{{ $loaRequest->corresponding_email }}</a>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Submission Date:</strong><br>
                    {{ $loaRequest->submission_date ? $loaRequest->submission_date->format('d F Y') : $loaRequest->created_at->format('d F Y') }}
                </div>
                
                @if($loaRequest->status === 'approved' && $loaRequest->approved_at)
                    <div class="mb-3">
                        <strong>Approved Date:</strong><br>
                        <span class="text-success">{{ $loaRequest->approved_at->format('d F Y H:i') }}</span>
                    </div>
                @endif
                
                @if($loaRequest->status === 'rejected' && $loaRequest->rejected_at)
                    <div class="mb-3">
                        <strong>Rejected Date:</strong><br>
                        <span class="text-danger">{{ $loaRequest->rejected_at->format('d F Y H:i') }}</span>
                    </div>
                    
                    @if($loaRequest->rejection_reason)
                        <div class="mb-3">
                            <strong>Rejection Reason:</strong><br>
                            <div class="alert alert-danger">{{ $loaRequest->rejection_reason }}</div>
                        </div>
                    @endif
                @endif
                
                @if($loaRequest->status === 'approved' && $loaRequest->loaValidated)
                    <div class="mb-3">
                        <strong>LOA Certificate:</strong><br>
                        <div class="d-flex gap-2">
                            <a href="{{ route('loa.view', $loaRequest->loaValidated->loa_code) }}" 
                               class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-2"></i>View Certificate
                            </a>
                            <a href="{{ route('loa.print', $loaRequest->loaValidated->loa_code) }}" 
                               class="btn btn-primary btn-sm" target="_blank">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        @if($loaRequest->status === 'pending')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Action Required</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">This LOA request is pending your review. Please approve or reject this request.</p>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="approveRequest({{ $loaRequest->id }})">
                            <i class="fas fa-check me-2"></i>Approve Request
                        </button>
                        <button type="button" class="btn btn-danger" onclick="rejectRequest({{ $loaRequest->id }})">
                            <i class="fas fa-times me-2"></i>Reject Request
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Journal Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($loaRequest->journal->logo)
                        <img src="{{ asset('storage/' . $loaRequest->journal->logo) }}" 
                             class="me-3 rounded" width="48" height="48">
                    @else
                        <div class="bg-success text-white rounded d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px;">
                            {{ strtoupper(substr($loaRequest->journal->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <strong>{{ $loaRequest->journal->name }}</strong><br>
                        <small class="text-muted">{{ $loaRequest->journal->publisher->name }}</small>
                    </div>
                </div>
                
                @if($loaRequest->journal->e_issn)
                    <div class="mb-2">
                        <strong>E-ISSN:</strong> {{ $loaRequest->journal->e_issn }}
                    </div>
                @endif
                
                @if($loaRequest->journal->p_issn)
                    <div class="mb-2">
                        <strong>P-ISSN:</strong> {{ $loaRequest->journal->p_issn }}
                    </div>
                @endif
                
                <div class="mb-2">
                    <strong>Chief Editor:</strong><br>
                    {{ $loaRequest->journal->chief_editor }}
                </div>
                
                @if($loaRequest->journal->website)
                    <div class="mb-2">
                        <strong>Website:</strong><br>
                        <a href="{{ $loaRequest->journal->website }}" target="_blank" class="text-primary">
                            {{ $loaRequest->journal->website }}
                            <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Request Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Request Submitted</h6>
                            <small class="text-muted">{{ $loaRequest->created_at->format('d F Y, H:i') }}</small>
                        </div>
                    </div>
                    
                    @if($loaRequest->status === 'approved' && $loaRequest->approved_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Request Approved</h6>
                                <small class="text-muted">{{ $loaRequest->approved_at->format('d F Y, H:i') }}</small>
                            </div>
                        </div>
                    @elseif($loaRequest->status === 'rejected' && $loaRequest->rejected_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Request Rejected</h6>
                                <small class="text-muted">{{ $loaRequest->rejected_at->format('d F Y, H:i') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }
</style>

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
</script>
@endsection
