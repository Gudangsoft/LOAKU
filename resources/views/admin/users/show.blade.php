@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Detail User</h1>
                    <p class="text-muted mb-0">Informasi lengkap user: {{ $user->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- User Profile Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Profil User</h6>
                                <span class="badge {{ $user->getRoleBadgeClass() }} fs-6">
                                    <i class="{{ $user->getRoleIcon() }} me-1"></i>
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center mb-3">
                                        <div class="avatar-lg bg-{{ $user->isAdministrator() ? 'danger' : 'primary' }} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                            <i class="{{ $user->getRoleIcon() }}" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <label class="text-muted small">NAMA LENGKAP</label>
                                            <div class="fw-medium">{{ $user->name }}</div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="text-muted small">EMAIL</label>
                                            <div class="d-flex align-items-center">
                                                <span>{{ $user->email }}</span>
                                                @if($user->email_verified_at)
                                                    <i class="fas fa-check-circle text-success ms-2" title="Email Terverifikasi"></i>
                                                @else
                                                    <i class="fas fa-exclamation-triangle text-warning ms-2" title="Email Belum Terverifikasi"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="text-muted small">ROLE</label>
                                            <div>
                                                <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'secondary' }}">
                                                    <i class="fas fa-{{ $user->isAdmin() ? 'shield-alt' : 'user' }} me-1"></i>
                                                    {{ $user->isAdmin() ? 'Administrator' : 'User' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="text-muted small">STATUS</label>
                                            <div>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Aktif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Permissions Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-key me-2"></i>
                                Permissions & Access
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($user->isAdministrator())
                                <div class="alert alert-danger mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Administrator Access</strong> - User ini memiliki akses penuh ke semua fitur sistem
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Dashboard Admin</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manajemen LOA Requests</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Approve/Reject Semua LOA</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manajemen Jurnal</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manajemen Publisher</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Template LOA</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manajemen User</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>System Configuration</li>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-user-edit me-2"></i>
                                    <strong>Member Access</strong> - User ini memiliki akses terbatas sebagai editor jurnal
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Create Publisher</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Create Jurnal</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Validasi LOA Jurnal Sendiri</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Statistik LOA Sendiri</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Admin Dashboard</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>LOA Jurnal Lain</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Manajemen User</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>System Configuration</li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit User
                                </a>
                                
                                @if($user->id !== auth()->id())
                                    <button type="button" 
                                            class="btn btn-{{ $user->isAdmin() ? 'outline-secondary' : 'outline-success' }}" 
                                            onclick="toggleRole({{ $user->id }})">
                                        <i class="fas fa-exchange-alt me-1"></i>
                                        {{ $user->isAdmin() ? 'Make User' : 'Make Admin' }}
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="deleteUser({{ $user->id }})">
                                        <i class="fas fa-trash me-1"></i>
                                        Delete User
                                    </button>
                                @else
                                    <div class="alert alert-warning d-inline-flex align-items-center mb-0 py-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <small>Anda tidak dapat mengubah atau menghapus akun sendiri</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- User Stats -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                User Statistics
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">ACCOUNT AGE</label>
                                <div class="fw-medium">{{ $user->created_at->diffForHumans() }}</div>
                                <div class="small text-muted">Terdaftar {{ $user->created_at->format('d M Y') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="text-muted small">LAST UPDATE</label>
                                <div class="fw-medium">{{ $user->updated_at->diffForHumans() }}</div>
                                <div class="small text-muted">{{ $user->updated_at->format('d M Y, H:i') }}</div>
                            </div>

                            @if($user->email_verified_at)
                                <div class="mb-0">
                                    <label class="text-muted small">EMAIL VERIFIED</label>
                                    <div class="fw-medium text-success">{{ $user->email_verified_at->diffForHumans() }}</div>
                                    <div class="small text-muted">{{ $user->email_verified_at->format('d M Y, H:i') }}</div>
                                </div>
                            @else
                                <div class="mb-0">
                                    <label class="text-muted small">EMAIL STATUS</label>
                                    <div class="fw-medium text-warning">Belum diverifikasi</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>
                                Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Profile
                                </a>
                                
                                @if(!$user->email_verified_at)
                                    <button class="btn btn-outline-success" onclick="verifyEmail({{ $user->id }})">
                                        <i class="fas fa-check me-2"></i>
                                        Verify Email
                                    </button>
                                    <button class="btn btn-outline-info" onclick="resendVerification({{ $user->id }})">
                                        <i class="fas fa-envelope me-2"></i>
                                        Kirim Ulang Verifikasi
                                    </button>
                                @else
                                    <button class="btn btn-outline-warning" onclick="unverifyEmail({{ $user->id }})">
                                        <i class="fas fa-times me-2"></i>
                                        Batalkan Verifikasi
                                    </button>
                                @endif
                                
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i>
                                    All Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-0">
                    Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?
                </p>
                <p class="text-center text-danger small mb-0">
                    Tindakan ini tidak dapat dibatalkan!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Hapus User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteUser(userId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function toggleRole(userId) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah role user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/toggle-role`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.error || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat mengubah role', 'error');
            });
        }
    });
}

function verifyEmail(userId) {
    Swal.fire({
        title: 'Verifikasi Email',
        text: 'Apakah Anda yakin ingin memverifikasi email user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Verifikasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/verify-email`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.error || 'Terjadi kesalahan saat memverifikasi email', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat memverifikasi email', 'error');
            });
        }
    });
}

function resendVerification(userId) {
    Swal.fire({
        title: 'Kirim Ulang Verifikasi',
        text: 'Apakah Anda yakin ingin mengirim ulang email verifikasi?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/resend-verification`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                } else {
                    Swal.fire('Error!', data.error || 'Terjadi kesalahan saat mengirim email verifikasi', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat mengirim email verifikasi', 'error');
            });
        }
    });
}

function unverifyEmail(userId) {
    Swal.fire({
        title: 'Batalkan Verifikasi',
        text: 'Apakah Anda yakin ingin membatalkan verifikasi email user ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/unverify-email`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.error || 'Terjadi kesalahan saat membatalkan verifikasi email', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat membatalkan verifikasi email', 'error');
            });
        }
    });
}
</script>
</script>
@endsection
