@extends('layouts.admin')

@section('title', 'Publisher Detail - ' . $publisher->name)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.publisher-validation.index') }}">Publisher Validation</a>
            </li>
            <li class="breadcrumb-item active">{{ $publisher->name }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="d-flex align-items-center">
            @if($publisher->logo)
                <img src="{{ Storage::url($publisher->logo) }}" 
                     alt="{{ $publisher->name }}" 
                     class="rounded me-3" 
                     style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-building text-white fa-2x"></i>
                </div>
            @endif
            <div>
                <h1 class="h3 mb-1">{{ $publisher->name }}</h1>
                <div class="d-flex align-items-center">
                    @if($publisher->status === 'active')
                        <span class="badge bg-success me-2">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                    @elseif($publisher->status === 'pending')
                        <span class="badge bg-warning text-dark me-2">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @else
                        <span class="badge bg-danger me-2">
                            <i class="fas fa-ban"></i> Suspended
                        </span>
                    @endif
                    
                    @if($publisher->type)
                        <span class="badge bg-secondary">{{ $publisher->type }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="btn-group">
            @if($publisher->status === 'pending')
                <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" 
                        data-bs-target="#activateModal">
                    <i class="fas fa-check"></i> Aktivasi
                </button>
                <button type="button" class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#suspendModal">
                    <i class="fas fa-ban"></i> Suspend
                </button>
            @elseif($publisher->status === 'active')
                <button type="button" class="btn btn-warning" 
                        onclick="regenerateToken({{ $publisher->id }})">
                    <i class="fas fa-sync-alt"></i> Regenerate Token
                </button>
                <button type="button" class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#suspendModal">
                    <i class="fas fa-ban"></i> Suspend
                </button>
            @else
                <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" 
                        data-bs-target="#activateModal">
                    <i class="fas fa-check"></i> Reaktivasi
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Publisher Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informasi Publisher
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Nama:</th>
                                    <td>{{ $publisher->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe:</th>
                                    <td>{{ $publisher->type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Website:</th>
                                    <td>
                                        @if($publisher->website)
                                            <a href="{{ $publisher->website }}" target="_blank" class="text-decoration-none">
                                                {{ $publisher->website }} <i class="fas fa-external-link-alt fa-xs"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $publisher->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon:</th>
                                    <td>{{ $publisher->phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Contact Person:</th>
                                    <td>{{ $publisher->contact_person ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>ISSN:</th>
                                    <td>{{ $publisher->issn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>DOI Prefix:</th>
                                    <td>{{ $publisher->doi_prefix ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Daftar:</th>
                                    <td>
                                        {{ $publisher->created_at->format('d M Y H:i') }}
                                        <small class="text-muted">({{ $publisher->created_at->diffForHumans() }})</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($publisher->address)
                        <hr>
                        <div class="mb-3">
                            <strong>Alamat:</strong><br>
                            {{ $publisher->address }}
                        </div>
                    @endif

                    @if($publisher->description)
                        <hr>
                        <div class="mb-3">
                            <strong>Deskripsi:</strong><br>
                            {{ $publisher->description }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Account Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Informasi Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Nama User:</th>
                                    <td>{{ $publisher->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $publisher->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $publisher->user->role }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Email Verified:</th>
                                    <td>
                                        @if($publisher->user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Verified
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $publisher->user->email_verified_at->format('d M Y H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Not Verified
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bergabung:</th>
                                    <td>
                                        {{ $publisher->user->created_at->format('d M Y H:i') }}
                                        <small class="text-muted">({{ $publisher->user->created_at->diffForHumans() }})</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Journals -->
            @if($publisher->journals->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-book"></i> Journals ({{ $publisher->journals->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Journal</th>
                                        <th>ISSN</th>
                                        <th>Website</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publisher->journals as $journal)
                                        <tr>
                                            <td>
                                                <strong>{{ $journal->name }}</strong>
                                                @if($journal->abbreviation)
                                                    <br><small class="text-muted">{{ $journal->abbreviation }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $journal->issn ?? '-' }}</td>
                                            <td>
                                                @if($journal->website)
                                                    <a href="{{ $journal->website }}" target="_blank" class="text-decoration-none">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-book text-muted" style="font-size: 2rem;"></i>
                        <h6 class="text-muted mt-2">Belum ada journal terdaftar</h6>
                    </div>
                </div>
            @endif
        </div>

        <!-- Validation Status Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Status Validasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($publisher->status === 'active')
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            <h6 class="text-success mt-2">AKTIF</h6>
                        @elseif($publisher->status === 'pending')
                            <i class="fas fa-clock text-warning" style="font-size: 3rem;"></i>
                            <h6 class="text-warning mt-2">MENUNGGU VALIDASI</h6>
                        @else
                            <i class="fas fa-ban text-danger" style="font-size: 3rem;"></i>
                            <h6 class="text-danger mt-2">DISUSPEND</h6>
                        @endif
                    </div>

                    @if($publisher->validation_token)
                        <div class="mb-3">
                            <label class="form-label"><strong>Validation Token:</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                       value="{{ $publisher->validation_token }}" 
                                       readonly id="tokenInput">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="copyToken()" title="Copy Token">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($publisher->validated_at)
                        <div class="mb-3">
                            <label class="form-label"><strong>Tanggal Validasi:</strong></label>
                            <p class="mb-1">{{ $publisher->validated_at->format('d M Y H:i') }}</p>
                            <small class="text-muted">{{ $publisher->validated_at->diffForHumans() }}</small>
                        </div>
                    @endif

                    @if($publisher->validator)
                        <div class="mb-3">
                            <label class="form-label"><strong>Divalidasi oleh:</strong></label>
                            <p class="mb-0">{{ $publisher->validator->name }}</p>
                        </div>
                    @endif

                    @if($publisher->validation_notes)
                        <div class="mb-3">
                            <label class="form-label"><strong>Catatan Validasi:</strong></label>
                            <div class="bg-light rounded p-2">
                                <small>{{ $publisher->validation_notes }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history"></i> Riwayat Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pendaftaran</h6>
                                <p class="timeline-text">{{ $publisher->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($publisher->validated_at)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $publisher->status === 'active' ? 'bg-success' : 'bg-danger' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">
                                        {{ $publisher->status === 'active' ? 'Diaktifkan' : 'Disuspend' }}
                                    </h6>
                                    <p class="timeline-text">{{ $publisher->validated_at->format('d M Y H:i') }}</p>
                                    @if($publisher->validator)
                                        <small class="text-muted">oleh {{ $publisher->validator->name }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activate Modal -->
<div class="modal fade" id="activateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.publisher-validation.activate', $publisher) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> 
                        {{ $publisher->status === 'pending' ? 'Aktivasi' : 'Reaktivasi' }} Publisher
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Publisher:</strong> {{ $publisher->name }}<br>
                        <strong>Contact:</strong> {{ $publisher->user->name }} ({{ $publisher->user->email }})
                    </div>
                    <div class="mb-3">
                        <label for="validation_notes" class="form-label">
                            Catatan Validasi (Opsional)
                        </label>
                        <textarea class="form-control" 
                                 id="validation_notes" 
                                 name="validation_notes" 
                                 rows="3" 
                                 placeholder="Tambahkan catatan untuk aktivasi ini..."></textarea>
                    </div>
                    @if($publisher->validation_token)
                        <div class="alert alert-info">
                            <strong>Token saat ini:</strong> <code>{{ $publisher->validation_token }}</code>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Token baru akan dibuat secara otomatis.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> {{ $publisher->status === 'pending' ? 'Aktivasi' : 'Reaktivasi' }} Publisher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.publisher-validation.suspend', $publisher) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-ban"></i> Suspend Publisher
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Publisher:</strong> {{ $publisher->name }}<br>
                        <strong>Contact:</strong> {{ $publisher->user->name }} ({{ $publisher->user->email }})
                    </div>
                    <div class="mb-3">
                        <label for="suspend_notes" class="form-label">
                            Alasan Suspend <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                 id="suspend_notes" 
                                 name="validation_notes" 
                                 rows="3" 
                                 placeholder="Jelaskan alasan suspend..."
                                 required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Publisher akan kehilangan akses ke sistem sampai diaktifkan kembali.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i> Suspend Publisher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function copyToken() {
    const tokenInput = document.getElementById('tokenInput');
    tokenInput.select();
    tokenInput.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(tokenInput.value);
    
    // Show feedback
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('btn-success');
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
    }, 1000);
}

function regenerateToken(publisherId) {
    if (confirm('Yakin ingin membuat token baru? Token lama akan tidak valid.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.publisher-validation.regenerate-token", ":id") }}'.replace(':id', publisherId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    bottom: -20px;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0;
}

.table-borderless th {
    border: none;
    padding: 0.25rem 0.5rem;
    font-weight: 600;
    color: #495057;
}

.table-borderless td {
    border: none;
    padding: 0.25rem 0.5rem;
}
</style>
@endpush
