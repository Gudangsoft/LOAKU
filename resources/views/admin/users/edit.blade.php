@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Edit User</h1>
                    <p class="text-muted mb-0">Edit data user: {{ $user->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-1"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">Form Edit User</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required
                                           placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required
                                           placeholder="contoh@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="member" {{ old('role', $user->role) === 'member' ? 'selected' : '' }}>
                                            Member (Pengguna Biasa)
                                        </option>
                                        <option value="publisher" {{ old('role', $user->role) === 'publisher' ? 'selected' : '' }}>
                                            Publisher (Editor Jurnal)
                                        </option>
                                        <option value="administrator" {{ old('role', $user->role) === 'administrator' || $user->isAdmin() ? 'selected' : '' }}>
                                            Administrator (Full Access)
                                        </option>
                                    </select>
                                    @if($user->id === auth()->id())
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                        <div class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Anda tidak dapat mengubah role akun sendiri
                                        </div>
                                    @endif
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               minlength="8"
                                               placeholder="Masukkan password baru">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               minlength="8"
                                               placeholder="Ulangi password baru">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Simpan Perubahan
                                    </button>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye me-1"></i>
                                        Lihat Detail
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi User
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">STATUS AKUN</label>
                                <div class="mt-1">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>
                                        Aktif
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">ROLE SAAT INI</label>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'secondary' }} fs-6">
                                        <i class="fas fa-{{ $user->isAdmin() ? 'shield-alt' : 'user' }} me-1"></i>
                                        {{ $user->isAdmin() ? 'Admin' : 'User' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">EMAIL VERIFIED</label>
                                <div class="mt-1">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Terverifikasi
                                        </span>
                                        <div class="small text-muted mt-1">
                                            {{ $user->email_verified_at->format('d M Y, H:i') }}
                                        </div>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            Belum Terverifikasi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">TERDAFTAR</label>
                                <div class="mt-1">
                                    <span class="text-dark">{{ $user->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="text-muted small">TERAKHIR UPDATE</label>
                                <div class="mt-1">
                                    <span class="text-dark">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
@endsection
