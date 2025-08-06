@extends('layouts.member')

@section('title', 'Profil Saya')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="mb-1">
            <i class="fas fa-user-cog me-2"></i>Profil Saya
        </h2>
        <p class="text-muted mb-0">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('member.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="organization" class="form-label">Institusi/Organisasi</label>
                                    <input type="text" class="form-control @error('organization') is-invalid @enderror" 
                                           id="organization" name="organization" value="{{ old('organization', Auth::user()->organization) }}">
                                    @error('organization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('member.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required minlength="6">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required minlength="6">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Profile Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>Ringkasan Profil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                    </div>
                    <h5>{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <span class="badge bg-info">Member</span>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="mb-0">{{ Auth::user()->loa_requests_count ?? 0 }}</h6>
                            <small class="text-muted">LOA Requests</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0">{{ Auth::user()->created_at->format('M Y') }}</h6>
                            <small class="text-muted">Bergabung</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Security -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Keamanan Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Email Terverifikasi</h6>
                            <small class="text-muted">Status verifikasi email Anda</small>
                        </div>
                        <span class="badge bg-success">
                            <i class="fas fa-check"></i>
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Password</h6>
                            <small class="text-muted">Terakhir diubah: {{ Auth::user()->updated_at->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge bg-secondary">
                            <i class="fas fa-key"></i>
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Akun Aktif</h6>
                            <small class="text-muted">Status akun member</small>
                        </div>
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
