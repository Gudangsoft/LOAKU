@extends('layouts.admin')

@section('title', 'LOA Requests')
@section('subtitle', 'Manage and review all LOA requests')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt me-2"></i>
                Kelola Permintaan LOA
            </h1>
            <p class="mb-0 text-muted">Kelola dan review semua permintaan LOA dari pengguna</p>
        </div>
    </div>

    <!-- Filter Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h5>{{ $requests->where('status', 'pending')->count() }}</h5>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h5>{{ $requests->where('status', 'approved')->count() }}</h5>
                    <small>Disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h5>{{ $requests->where('status', 'rejected')->count() }}</h5>
                    <small>Ditolak</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <h5>{{ $requests->total() }}</h5>
                    <small>Total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>
                Daftar Permintaan LOA
            </h6>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Reg</th>
                                <th>No. LOA</th>
                                <th>Judul Artikel</th>
                                <th>Penulis</th>
                                <th>Edisi</th>
                                <th>Jurnal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $request->no_reg }}</span>
                                    </td>
                                    <td>
                                        @if($request->status === 'approved')
                                            @php
                                                $validatedLoa = $request->loaValidated;
                                            @endphp
                                            @if($validatedLoa)
                                                <span class="badge bg-success px-2 py-1" 
                                                      title="LOA Terverifikasi: {{ $validatedLoa->loa_code }}"
                                                      data-bs-toggle="tooltip">
                                                    <i class="fas fa-certificate me-1"></i>
                                                    {{ $validatedLoa->loa_code }}
                                                </span>
                                            @else
                                                <span class="text-muted small" title="LOA belum dibuat">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Belum dibuat
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $request->article_title }}">
                                            <strong>{{ $request->article_title }}</strong>
                                        </div>
                                        <small class="text-muted">ID: {{ $request->article_id }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $request->author }}</div>
                                        <small class="text-muted">{{ $request->author_email }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $request->edition }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $request->journal->name }}</div>
                                        <small class="text-muted">{{ $request->journal->publisher->name }}</small>
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $request->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.loa-requests.show', $request) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($request->status === 'pending')
                                                <form action="{{ route('admin.loa-requests.approve', $request) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-success btn-sm" 
                                                            title="Setujui"
                                                            onclick="return confirm('Yakin ingin menyetujui permintaan ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Tolak"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $request->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Reject Modal -->
                                @if($request->status === 'pending')
                                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.loa-requests.reject', $request) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Permintaan LOA</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Artikel:</strong> {{ $request->article_title }}</p>
                                                    <p><strong>Penulis:</strong> {{ $request->author }}</p>
                                                    
                                                    <div class="mb-3">
                                                        <label for="admin_notes" class="form-label">
                                                            Alasan Penolakan <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea class="form-control" 
                                                                  id="admin_notes" 
                                                                  name="admin_notes" 
                                                                  rows="3" 
                                                                  placeholder="Berikan alasan penolakan yang jelas..."
                                                                  required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times me-1"></i>
                                                        Tolak Permintaan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada permintaan LOA</h5>
                    <p class="text-muted">Permintaan LOA dari pengguna akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
