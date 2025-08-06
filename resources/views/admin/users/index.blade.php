@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Manajemen User</h1>
                    <p class="text-muted mb-0">Kelola user dan pengaturan role</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tambah User
                    </a>
                    <a href="{{ route('admin.create-admin') }}" class="btn btn-success">
                        <i class="fas fa-user-shield me-1"></i>
                        Create Admin
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">Daftar User</h6>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-info">{{ $users->total() }} Total User</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Terdaftar</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $index => $user)
                                        <tr>
                                            <td>{{ $users->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-{{ $user->isAdmin() ? 'danger' : 'primary' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-{{ $user->isAdmin() ? 'user-shield' : 'user' }}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $user->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $user->email }}</span>
                                                @if($user->email_verified_at)
                                                    <i class="fas fa-check-circle text-success ms-1" title="Email Verified"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->getRoleBadgeClass() }}">
                                                    <i class="{{ $user->getRoleIcon() }} me-1"></i>
                                                    {{ $user->getRoleDisplayName() }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Aktif
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->created_at->format('d M Y, H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.users.show', $user) }}" 
                                                       class="btn btn-outline-info" 
                                                       title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                                       class="btn btn-outline-warning" 
                                                       title="Edit User">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <button type="button" 
                                                                class="btn btn-outline-{{ $user->isAdministrator() ? 'secondary' : 'success' }}" 
                                                                onclick="toggleRole({{ $user->id }})"
                                                                title="Toggle Role">
                                                            <i class="fas fa-exchange-alt"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger" 
                                                                onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                                title="Hapus User">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($users->hasPages())
                            <div class="card-footer bg-white">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">Belum ada user terdaftar</h5>
                            <p class="text-muted mb-4">Tambah user pertama untuk memulai</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Tambah User Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    console.log('jQuery loaded:', typeof $ !== 'undefined');
    console.log('SweetAlert loaded:', typeof Swal !== 'undefined');
    
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function confirmDeleteUser(userId, userName) {
    console.log('Delete user function called:', userId, userName);
    
    if (typeof Swal === 'undefined') {
        // Fallback to native confirm if SweetAlert not available
        if (confirm(`Apakah Anda yakin ingin menghapus user "${userName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
            submitDeleteForm(userId);
        }
        return;
    }
    
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus user <br><strong>"${userName}"</strong>?<br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times me-1"></i>Batal',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            submitDeleteForm(userId);
        }
    });
}

function submitDeleteForm(userId) {
    // Create and submit delete form
    const form = $('<form>', {
        method: 'POST',
        action: `/admin/users/${userId}`
    });
    
    // Add CSRF token
    form.append($('<input>', {
        type: 'hidden',
        name: '_token',
        value: $('meta[name="csrf-token"]').attr('content')
    }));
    
    // Add DELETE method
    form.append($('<input>', {
        type: 'hidden',
        name: '_method',
        value: 'DELETE'
    }));
    
    // Submit form
    $('body').append(form);
    form.submit();
}

function toggleRole(userId) {
    console.log('Toggle role function called:', userId);
    
    if (typeof Swal === 'undefined') {
        if (!confirm('Apakah Anda yakin ingin mengubah role user ini?')) {
            return;
        }
    } else {
        Swal.fire({
            title: 'Konfirmasi Perubahan Role',
            text: 'Apakah Anda yakin ingin mengubah role user ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-exchange-alt me-1"></i>Ya, Ubah!',
            cancelButtonText: '<i class="fas fa-times me-1"></i>Batal',
            customClass: {
                confirmButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                performToggleRole(userId);
            }
        });
        return;
    }
    
    performToggleRole(userId);
}

function performToggleRole(userId) {
    $.ajax({
        url: `/admin/users/${userId}/toggle-role`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message || 'Role berhasil diubah',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    alert(data.message || 'Role berhasil diubah');
                    location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: data.error || 'Terjadi kesalahan',
                        icon: 'error'
                    });
                } else {
                    alert(data.error || 'Terjadi kesalahan');
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.error('Response:', xhr.responseText);
            
            const errorMsg = 'Terjadi kesalahan saat mengubah role';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error'
                });
            } else {
                alert(errorMsg);
            }
        }
    });
}
</script>
@endsection
