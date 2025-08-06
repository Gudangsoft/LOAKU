@extends('layouts.app')

@section('title', 'Register - LOA SIPTENAN')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Daftar Akun Baru
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Bergabunglah dengan LOA SIPTENAN</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-1"></i>Nama Lengkap
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name" 
                                   autofocus
                                   placeholder="Masukkan nama lengkap Anda">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email"
                                   placeholder="contoh@email.com">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Minimal 6 karakter">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Konfirmasi Password
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Ulangi password Anda">
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Login di sini
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4 border-info">
                <div class="card-body text-center">
                    <h6 class="card-title text-info">
                        <i class="fas fa-info-circle me-1"></i>Manfaat Mendaftar
                    </h6>
                    <ul class="list-unstyled text-start mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>Request LOA dengan mudah</li>
                        <li><i class="fas fa-check text-success me-2"></i>Track status permohonan</li>
                        <li><i class="fas fa-check text-success me-2"></i>Download LOA yang telah disetujui</li>
                        <li><i class="fas fa-check text-success me-2"></i>Verifikasi keaslian LOA</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
