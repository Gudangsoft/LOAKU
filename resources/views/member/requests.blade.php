@extends('layouts.member')

@section('title', 'Request Saya')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-list-alt me-2"></i>Request LOA Saya
            </h2>
            <p class="text-muted mb-0">Kelola semua LOA request yang telah Anda ajukan</p>
        </div>
        <a href="{{ route('loa.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Request LOA Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $requests->count() }}</h4>
                            <small>Total Request</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
                            <small>Disetujui</small>
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
                            <small>Ditolak</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i>Daftar Request LOA
            </h5>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Artikel</th>
                                <th>Jurnal</th>
                                <th>Tanggal Request</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $index => $request)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $request->article_title }}</strong>
                                    <br>
                                    <small class="text-muted">oleh {{ $request->author_name }}</small>
                                </td>
                                <td>
                                    {{ $request->journal->name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">{{ $request->journal->publisher->name ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-hourglass-half me-1"></i>Pending
                                        </span>
                                    @elseif($request->status == 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Disetujui
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewRequest({{ $request->id }})" 
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($request->status == 'approved' && $request->loaValidated)
                                            <a href="{{ route('loa.download', $request->loaValidated->id) }}" 
                                               class="btn btn-sm btn-success" 
                                               title="Download LOA">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        
                                        @if($request->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="editRequest({{ $request->id }})" 
                                                    title="Edit Request">
                                                <i class="fas fa-edit"></i>
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
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada LOA request. <a href="{{ route('loa.create') }}" class="alert-link">Buat request pertama Anda</a>.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Request Modal -->
<div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRequestModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Detail Request LOA
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="requestDetails">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewRequest(requestId) {
    $('#viewRequestModal').modal('show');
    
    // Load request details via AJAX
    $.get('/member/requests/' + requestId, function(data) {
        $('#requestDetails').html(`
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Artikel</h6>
                    <table class="table table-sm">
                        <tr><th>Judul:</th><td>${data.article_title}</td></tr>
                        <tr><th>Penulis:</th><td>${data.author_name}</td></tr>
                        <tr><th>Email Penulis:</th><td>${data.author_email}</td></tr>
                        <tr><th>Tanggal Submit:</th><td>${data.submission_date}</td></tr>
                        <tr><th>Tanggal Terima:</th><td>${data.acceptance_date}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-book me-2"></i>Informasi Jurnal</h6>
                    <table class="table table-sm">
                        <tr><th>Jurnal:</th><td>${data.journal ? data.journal.name : 'N/A'}</td></tr>
                        <tr><th>ISSN:</th><td>${data.journal ? data.journal.issn : 'N/A'}</td></tr>
                        <tr><th>Publisher:</th><td>${data.journal && data.journal.publisher ? data.journal.publisher.name : 'N/A'}</td></tr>
                    </table>
                    
                    <h6 class="mt-3"><i class="fas fa-flag me-2"></i>Status</h6>
                    <p>
                        Status: <span class="badge ${data.status == 'pending' ? 'bg-warning' : data.status == 'approved' ? 'bg-success' : 'bg-danger'}">
                            ${data.status == 'pending' ? 'Pending' : data.status == 'approved' ? 'Disetujui' : 'Ditolak'}
                        </span>
                    </p>
                    ${data.admin_notes ? '<p><strong>Catatan Admin:</strong> ' + data.admin_notes + '</p>' : ''}
                </div>
            </div>
        `);
    }).fail(function() {
        $('#requestDetails').html('<div class="alert alert-danger">Gagal memuat detail request.</div>');
    });
}

function editRequest(requestId) {
    window.location.href = '/loa/edit/' + requestId;
}
</script>
@endsection
