<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Backup Management - Test</title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.4.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Backup Management - TEST</h1>
            <p class="text-muted mb-0">Kelola backup database dan files sistem</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="showCreateBackupModal('database')">
                <i class="fas fa-database me-1"></i>Backup Database
            </button>
            <button class="btn btn-info" onclick="showCreateBackupModal('files')">
                <i class="fas fa-folder me-1"></i>Backup Files
            </button>
        </div>
    </div>

    <!-- Backup Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0" id="total-backups">-</div>
                            <div class="small">Total Backups</div>
                        </div>
                        <div class="h2 mb-0"><i class="fas fa-archive"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0" id="db-backups">-</div>
                            <div class="small">Database Backups</div>
                        </div>
                        <div class="h2 mb-0"><i class="fas fa-database"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0" id="files-backups">-</div>
                            <div class="small">Files Backups</div>
                        </div>
                        <div class="h2 mb-0"><i class="fas fa-folder"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0" id="total-size">-</div>
                            <div class="small">Total Size</div>
                        </div>
                        <div class="h2 mb-0"><i class="fas fa-hdd"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backups List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Backup Files</h5>
        </div>
        <div class="card-body">
            @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th>Age</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                            <tr>
                                <td>
                                    @if($backup['is_directory'])
                                        <i class="fas fa-folder me-2"></i>
                                    @else
                                        <i class="fas fa-file-archive me-2"></i>
                                    @endif
                                    <span class="font-monospace">{{ $backup['filename'] }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $backup['type'] === 'Files' ? 'bg-info' : 'bg-success' }}">
                                        {{ $backup['type'] }}
                                    </span>
                                </td>
                                <td>{{ $backup['size'] }}</td>
                                <td>{{ $backup['created_at']->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $backup['age'] }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if(!$backup['is_directory'])
                                            <a href="{{ route('admin.backups-test.download', ['filename' => urlencode($backup['filename'])]) }}" 
                                               class="btn btn-outline-primary" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        <button onclick="deleteBackup('{{ addslashes($backup['filename']) }}')" 
                                                class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-archive fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No backup files found</h5>
                    <p class="text-muted">Create your first backup using the buttons above.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="backupForm">
                    <div id="databaseOptions" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Backup Type</label>
                            <select class="form-select" name="type">
                                <option value="full">Full Backup (Structure + Data)</option>
                                <option value="schema">Schema Only (Structure)</option>
                                <option value="data">Data Only</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="compress" id="compress" checked>
                                <label class="form-check-label" for="compress">
                                    Compress backup file (recommended)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="filesOptions" style="display: none;">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_uploads" id="includeUploads" checked>
                                <label class="form-check-label" for="includeUploads">
                                    Include uploaded files (logos, documents)
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_logs" id="includeLogs">
                                <label class="form-check-label" for="includeLogs">
                                    Include log files (last 30 days)
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createBackup()">
                    <i class="fas fa-play me-1"></i>Create Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Creating Backup...</h5>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Please wait while the backup is being created...</p>
                <div id="progressOutput" class="text-start mt-3" style="display: none;">
                    <pre class="bg-light p-2 rounded small"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let currentBackupType = '';

// Load statistics on page load
$(document).ready(function() {
    loadBackupStats();
});

function loadBackupStats() {
    $.get('{{ route("admin.backups-test.stats") }}')
        .done(function(data) {
            $('#total-backups').text(data.total_backups);
            $('#db-backups').text(data.database_backups);
            $('#files-backups').text(data.files_backups);
            $('#total-size').text(data.total_size);
        })
        .fail(function() {
            console.error('Failed to load backup statistics');
        });
}

function showCreateBackupModal(type) {
    currentBackupType = type;
    
    if (type === 'database') {
        $('#modalTitle').text('Create Database Backup');
        $('#databaseOptions').show();
        $('#filesOptions').hide();
    } else {
        $('#modalTitle').text('Create Files Backup');
        $('#databaseOptions').hide();
        $('#filesOptions').show();
    }
    
    $('#createBackupModal').modal('show');
}

function createBackup() {
    const formData = new FormData(document.getElementById('backupForm'));
    
    // Show progress modal
    $('#createBackupModal').modal('hide');
    $('#progressModal').modal('show');
    
    const url = currentBackupType === 'database' 
        ? '{{ route("admin.backups-test.create-database") }}'
        : '{{ route("admin.backups-test.create-files") }}';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .done(function(response) {
        $('#progressModal').modal('hide');
        
        if (response.success) {
            Swal.fire({
                title: 'Success!',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: response.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .fail(function(xhr) {
        $('#progressModal').modal('hide');
        
        let message = 'An error occurred while creating the backup.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        
        Swal.fire({
            title: 'Error!',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

function deleteBackup(filename) {
    Swal.fire({
        title: 'Delete Backup?',
        text: `Are you sure you want to delete ${filename}? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ route('admin.backups-test.delete', '') }}/${filename}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .done(function(response) {
                if (response.success) {
                    Swal.fire('Deleted!', response.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            })
            .fail(function(xhr) {
                let message = 'Failed to delete backup file.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error!', message, 'error');
            });
        }
    });
}
</script>
</body>
</html>
