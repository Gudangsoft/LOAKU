@extends('layouts.app')

@section('title', 'Login - LOA SIPTENAN')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Masuk ke akun LOA SIPTENAN Anda</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
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
                                   autofocus
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
                                   autocomplete="current-password"
                                   placeholder="Masukkan password Anda">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-1"></i>Daftar di sini
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Admin Login Card -->
            <div class="card mt-4 border-primary">
                <div class="card-body text-center">
                    <h6 class="card-title text-primary">
                        <i class="fas fa-user-shield me-1"></i>Admin/Publisher?
                    </h6>
                    <p class="card-text">Akses dashboard admin untuk mengelola sistem LOA</p>
                    <a href="{{ route('admin.login') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt me-1"></i>Login Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
