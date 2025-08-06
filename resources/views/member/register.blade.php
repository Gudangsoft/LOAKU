@extends('layouts.app')

@section('title', 'Register Member - LOA SIPTENAN')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Daftar Member LOA
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Akses khusus untuk mengelola Publisher & Jurnal</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('member.register') }}">
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

                        <!-- Phone Field -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>No. Telepon
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="0812-3456-7890">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Organization Field -->
                        <div class="mb-3">
                            <label for="organization" class="form-label">
                                <i class="fas fa-building me-1"></i>Organisasi/Institusi
                            </label>
                            <input type="text" 
                                   class="form-control @error('organization') is-invalid @enderror" 
                                   id="organization" 
                                   name="organization" 
                                   value="{{ old('organization') }}" 
                                   placeholder="Nama institusi/organisasi">
                            @error('organization')
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
                            <button type="submit" class="btn btn-warning btn-lg text-dark">
                                <i class="fas fa-user-tie me-2"></i>Daftar sebagai Member
                            </button>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun member?</p>
                            <a href="{{ route('member.login') }}" class="btn btn-outline-warning">
                                <i class="fas fa-sign-in-alt me-1"></i>Login Member
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4 border-warning">
                <div class="card-body text-center">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-crown me-1"></i>Hak Akses Member
                    </h6>
                    <ul class="list-unstyled text-start mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>Mengelola data Publisher</li>
                        <li><i class="fas fa-check text-success me-2"></i>Mengelola data Jurnal</li>
                        <li><i class="fas fa-check text-success me-2"></i>Approve/Reject permintaan LOA</li>
                        <li><i class="fas fa-check text-success me-2"></i>Melihat statistik lengkap</li>
                        <li><i class="fas fa-check text-success me-2"></i>Akses dashboard khusus member</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
