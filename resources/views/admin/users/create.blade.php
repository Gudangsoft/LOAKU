@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Tambah User Baru</h1>
                    <p class="text-muted mb-0">Buat akun user baru dengan role yang sesuai</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8 col-xl-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">Form Tambah User</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.users.store') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
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
                                           value="{{ old('email') }}" 
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
                                            required>
                                        <option value="">Pilih Role</option>
                                        <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>
                                            Member (Pengguna Biasa)
                                        </option>
                                        <option value="publisher" {{ old('role') === 'publisher' ? 'selected' : '' }}>
                                            Publisher (Editor Jurnal)
                                        </option>
                                        <option value="administrator" {{ old('role') === 'administrator' ? 'selected' : '' }}>
                                            Administrator (Full Access)
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Administrator:</strong> Akses penuh ke semua fitur sistem<br>
                                            <strong>Publisher:</strong> Kelola publisher, jurnal, dan validasi LOA jurnal sendiri<br>
                                            <strong>Member:</strong> Request LOA, Search & Download LOA, Verify LOA
                                        </small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required
                                               minlength="8"
                                               placeholder="Minimal 8 karakter">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required
                                               minlength="8"
                                               placeholder="Ulangi password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Simpan User
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xl-6">
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Role
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-sm bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <strong>User (Pengguna Biasa)</strong>
                                </div>
                                <ul class="list-unstyled ms-4 text-sm">
                                    <li><i class="fas fa-check text-success me-2"></i>Request LOA</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Search & Download LOA</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Verify LOA</li>
                                    <li><i class="fas fa-times text-danger me-2"></i>Akses Admin Dashboard</li>
                                </ul>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <strong>Publisher (Editor Jurnal)</strong>
                                </div>
                                <ul class="list-unstyled ms-4 text-sm">
                                    <li><i class="fas fa-check text-success me-2"></i>Semua fitur User</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Publisher Dashboard</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Buat & Kelola Publisher</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Buat & Kelola Jurnal</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Approve/Reject LOA Request</li>
                                    <li><i class="fas fa-times text-danger me-2"></i>Akses Admin Dashboard</li>
                                </ul>
                            </div>

                            <hr>

                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <strong>Admin (Administrator)</strong>
                                </div>
                                <ul class="list-unstyled ms-4 text-sm">
                                    <li><i class="fas fa-check text-success me-2"></i>Semua fitur User & Publisher</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Admin Dashboard</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Kelola Semua Publisher & Jurnal</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Kelola Semua LOA Request</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Manajemen User</li>
                                    <li><i class="fas fa-check text-success me-2"></i>System Configuration</li>
                                </ul>
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
