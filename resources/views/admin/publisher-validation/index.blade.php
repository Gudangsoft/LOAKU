@extends('layouts.admin')

@section('title', 'Publisher Validation Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Publisher Validation Management</h1>
            <p class="text-muted">Kelola validasi dan aktivasi publisher</p>
        </div>
        <div>
            <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#statisticsModal">
                <i class="fas fa-chart-bar"></i> Statistik
            </button>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkActivateModal" 
                    {{ $pendingPublishers->count() === 0 ? 'disabled' : '' }}>
                <i class="fas fa-users-cog"></i> Bulk Activate
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="publisherTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                <i class="fas fa-clock text-warning"></i> Pending 
                <span class="badge bg-warning text-dark ms-1">{{ $pendingPublishers->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                <i class="fas fa-check-circle text-success"></i> Active 
                <span class="badge bg-success ms-1">{{ $activePublishers->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="suspended-tab" data-bs-toggle="tab" data-bs-target="#suspended" type="button" role="tab">
                <i class="fas fa-ban text-danger"></i> Suspended 
                <span class="badge bg-danger ms-1">{{ $suspendedPublishers->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="publisherTabsContent">
        <!-- Pending Publishers Tab -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Publisher Menunggu Validasi
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingPublishers->count() === 0)
                        <div class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Tidak ada publisher yang menunggu validasi</h5>
                            <p class="text-muted">Semua publisher sudah divalidasi atau belum ada pendaftar baru.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Publisher</th>
                                        <th>Contact</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Token</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPublishers as $publisher)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input publisher-checkbox" type="checkbox" 
                                                       value="{{ $publisher->id }}" name="publisher_ids[]">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($publisher->logo)
                                                    <img src="{{ Storage::url($publisher->logo) }}" 
                                                         alt="{{ $publisher->name }}" 
                                                         class="rounded me-3" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-building text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $publisher->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $publisher->type ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($publisher->user)
                                                <strong>{{ $publisher->user->name }}</strong><br>
                                                <small class="text-muted">{{ $publisher->user->email }}</small><br>
                                            @else
                                                <strong class="text-danger">User tidak ditemukan</strong><br>
                                                <small class="text-muted">ID: {{ $publisher->user_id ?? 'N/A' }}</small><br>
                                            @endif
                                            @if($publisher->contact_person)
                                                <small class="text-muted">{{ $publisher->contact_person }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $publisher->created_at->format('d M Y H:i') }}</small><br>
                                            <small class="text-muted">{{ $publisher->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($publisher->validation_token)
                                                <code class="bg-light px-2 py-1 rounded">{{ $publisher->validation_token }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.publisher-validation.show', $publisher) }}" 
                                                   class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#activateModal{{ $publisher->id }}" 
                                                        title="Activate">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#suspendModal{{ $publisher->id }}" 
                                                        title="Suspend">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Active Publishers Tab -->
        <div class="tab-pane fade" id="active" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle"></i> Publisher Aktif
                    </h5>
                </div>
                <div class="card-body">
                    @if($activePublishers->count() === 0)
                        <div class="text-center py-4">
                            <i class="fas fa-users-slash text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Belum ada publisher yang aktif</h5>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Publisher</th>
                                        <th>Contact</th>
                                        <th>Diaktifkan</th>
                                        <th>Token</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activePublishers as $publisher)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($publisher->logo)
                                                    <img src="{{ Storage::url($publisher->logo) }}" 
                                                         alt="{{ $publisher->name }}" 
                                                         class="rounded me-3" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-building text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $publisher->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $publisher->type ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $publisher->user->name }}</strong><br>
                                            <small class="text-muted">{{ $publisher->user->email }}</small>
                                        </td>
                                        <td>
                                            @if($publisher->validated_at)
                                                <small>{{ $publisher->validated_at->format('d M Y H:i') }}</small><br>
                                                <small class="text-muted">oleh {{ $publisher->validator->name ?? 'System' }}</small>
                                            @else
                                                <small class="text-muted">Belum divalidasi</small>
                                            @endif
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $publisher->validation_token }}</code>
                                            <button type="button" class="btn btn-link btn-sm p-0 ms-1" 
                                                    onclick="regenerateToken({{ $publisher->id }})" 
                                                    title="Regenerate Token">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.publisher-validation.show', $publisher) }}" 
                                                   class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#suspendModal{{ $publisher->id }}" 
                                                        title="Suspend">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Suspended Publishers Tab -->
        <div class="tab-pane fade" id="suspended" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-ban"></i> Publisher Suspended
                    </h5>
                </div>
                <div class="card-body">
                    @if($suspendedPublishers->count() === 0)
                        <div class="text-center py-4">
                            <i class="fas fa-user-check text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Tidak ada publisher yang disuspend</h5>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Publisher</th>
                                        <th>Contact</th>
                                        <th>Disuspend</th>
                                        <th>Alasan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspendedPublishers as $publisher)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($publisher->logo)
                                                    <img src="{{ Storage::url($publisher->logo) }}" 
                                                         alt="{{ $publisher->name }}" 
                                                         class="rounded me-3" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-building text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $publisher->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $publisher->type ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $publisher->user->name }}</strong><br>
                                            <small class="text-muted">{{ $publisher->user->email }}</small>
                                        </td>
                                        <td>
                                            @if($publisher->validated_at)
                                                <small>{{ $publisher->validated_at->format('d M Y H:i') }}</small><br>
                                                <small class="text-muted">oleh {{ $publisher->validator->name ?? 'System' }}</small>
                                            @else
                                                <small class="text-muted">Belum divalidasi</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($publisher->validation_notes, 50) }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.publisher-validation.show', $publisher) }}" 
                                                   class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#activateModal{{ $publisher->id }}" 
                                                        title="Reactivate">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for all publishers -->
@foreach([$pendingPublishers, $activePublishers, $suspendedPublishers] as $publisherGroup)
    @foreach($publisherGroup as $publisher)
        <!-- Activate Modal -->
        <div class="modal fade" id="activateModal{{ $publisher->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.publisher-validation.activate', $publisher) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-check-circle"></i> Aktivasi Publisher
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>Publisher:</strong> {{ $publisher->name }}<br>
                                <strong>Contact:</strong> {{ $publisher->user->name }} ({{ $publisher->user->email }})
                            </div>
                            <div class="mb-3">
                                <label for="validation_notes{{ $publisher->id }}" class="form-label">
                                    Catatan Validasi (Opsional)
                                </label>
                                <textarea class="form-control" 
                                         id="validation_notes{{ $publisher->id }}" 
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
                                <i class="fas fa-check"></i> Aktivasi Publisher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Suspend Modal -->
        <div class="modal fade" id="suspendModal{{ $publisher->id }}" tabindex="-1">
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
                                <label for="suspend_notes{{ $publisher->id }}" class="form-label">
                                    Alasan Suspend <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                         id="suspend_notes{{ $publisher->id }}" 
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
    @endforeach
@endforeach

<!-- Bulk Activate Modal -->
<div class="modal fade" id="bulkActivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.publisher-validation.bulk-activate') }}" method="POST" id="bulkActivateForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-users-cog"></i> Bulk Activate Publishers
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Publisher yang dipilih:</label>
                        <div id="selectedPublishers" class="border rounded p-2 bg-light">
                            <em class="text-muted">Pilih publisher dari tab Pending...</em>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_notes" class="form-label">Catatan Bulk Aktivasi (Opsional)</label>
                        <textarea class="form-control" 
                                 id="bulk_notes" 
                                 name="bulk_notes" 
                                 rows="3" 
                                 placeholder="Catatan untuk semua publisher yang diaktivasi..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Token akan dibuat otomatis untuk setiap publisher.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="bulkActivateBtn" disabled>
                        <i class="fas fa-users-cog"></i> Aktivasi Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar"></i> Publisher Statistics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="statisticsContent">
                    <div class="col-12 text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat statistik...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.publisher-checkbox').prop('checked', this.checked);
        updateBulkActivateButton();
    });

    // Individual checkbox change
    $('.publisher-checkbox').change(function() {
        updateBulkActivateButton();
        
        // Update select all checkbox
        const totalCheckboxes = $('.publisher-checkbox').length;
        const checkedCheckboxes = $('.publisher-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Update bulk activate button and selected publishers display
    function updateBulkActivateButton() {
        const selectedIds = $('.publisher-checkbox:checked').map(function() {
            return this.value;
        }).get();

        const selectedCount = selectedIds.length;
        $('#bulkActivateBtn').prop('disabled', selectedCount === 0);

        if (selectedCount > 0) {
            const selectedNames = $('.publisher-checkbox:checked').map(function() {
                return $(this).closest('tr').find('strong').first().text();
            }).get();

            $('#selectedPublishers').html(
                '<strong>' + selectedCount + ' publisher dipilih:</strong><br>' +
                selectedNames.join(', ')
            );

            // Add hidden inputs for selected IDs
            $('#bulkActivateForm input[name="publisher_ids[]"]').remove();
            selectedIds.forEach(function(id) {
                $('#bulkActivateForm').append('<input type="hidden" name="publisher_ids[]" value="' + id + '">');
            });
        } else {
            $('#selectedPublishers').html('<em class="text-muted">Pilih publisher dari tab Pending...</em>');
        }
    }

    // Load statistics when modal is shown
    $('#statisticsModal').on('show.bs.modal', function() {
        loadStatistics();
    });

    function loadStatistics() {
        $.get('{{ route("admin.publisher-validation.statistics") }}')
            .done(function(data) {
                $('#statisticsContent').html(`
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="text-warning">${data.pending}</h2>
                                <p class="card-text">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="text-success">${data.active}</h2>
                                <p class="card-text">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="text-danger">${data.suspended}</h2>
                                <p class="card-text">Suspended</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-info">${data.today_registrations}</h3>
                                <p class="card-text">Registrasi Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-primary">${data.today_activations}</h3>
                                <p class="card-text">Aktivasi Hari Ini</p>
                            </div>
                        </div>
                    </div>
                `);
            })
            .fail(function() {
                $('#statisticsContent').html('<div class="col-12 text-center text-danger">Error loading statistics</div>');
            });
    }
});

// Regenerate token function
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
.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
}

.nav-tabs .nav-link.active {
    background-color: transparent;
    border-bottom-color: #007bff;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.code {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}
</style>
@endpush
