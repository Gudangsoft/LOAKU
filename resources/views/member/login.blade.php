@extends('layouts.app')

@section('title', 'Login Member - LOA SIPTENAN')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Login Member
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Masuk ke akun member LOA SIPTENAN</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('member.login') }}">
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
                            <button type="submit" class="btn btn-warning btn-lg text-dark">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Member
                            </button>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun member?</p>
                            <a href="{{ route('member.register') }}" class="btn btn-outline-warning">
                                <i class="fas fa-user-plus me-1"></i>Daftar Member
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Other Login Options -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-success">
                                <i class="fas fa-user me-1"></i>User Biasa?
                            </h6>
                            <a href="{{ route('login') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i>Login User
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h6 class="card-title text-primary">
                                <i class="fas fa-user-shield me-1"></i>Administrator?
                            </h6>
                            <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-tachometer-alt me-1"></i>Login Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
