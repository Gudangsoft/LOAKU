@extends('layouts.app')

@section('title', 'Publisher Validation - Token Input')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5 text-center">
                        <!-- Status Icon -->
                        <div class="mb-4">
                            <i class="fas fa-key text-success" style="font-size: 4rem;"></i>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="h3 mb-3 text-dark">Aktivasi Publisher</h1>
                        <p class="text-muted mb-4">
                            Akun Publisher Anda telah disetujui! Silakan masukkan token aktivasi yang diberikan administrator.
                        </p>

                        <!-- Token Input Form -->
                        <form action="{{ route('publisher.validation.token.submit') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="validation_token" class="form-label text-start d-block">
                                    <strong>Token Aktivasi</strong>
                                </label>
                                <input type="text" 
                                       id="validation_token" 
                                       name="validation_token" 
                                       class="form-control form-control-lg text-center @error('validation_token') is-invalid @enderror" 
                                       placeholder="Masukkan 8 karakter token"
                                       style="letter-spacing: 0.2em; font-family: 'Courier New', monospace; border-radius: 10px;"
                                       maxlength="8"
                                       autocomplete="off"
                                       value="{{ old('validation_token') }}"
                                       required>
                                
                                @error('validation_token')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100" style="border-radius: 10px;">
                                <i class="fas fa-unlock"></i> Aktivasi Akun Publisher
                            </button>
                        </form>

                        <!-- Information -->
                        <div class="alert alert-info text-start">
                            <h6><i class="fas fa-lightbulb"></i> Informasi Token:</h6>
                            <ul class="mb-0">
                                <li>Token terdiri dari 8 karakter alphanumerik</li>
                                <li>Token diberikan oleh administrator setelah validasi</li>
                                <li>Token bersifat case-sensitive (huruf besar/kecil berpengaruh)</li>
                                <li>Setiap token hanya dapat digunakan sekali</li>
                            </ul>
                        </div>

                        @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                        @endif

                        <div class="text-muted mt-4">
                            <p class="mb-1">
                                <i class="fas fa-question-circle"></i>
                                Belum menerima token?
                            </p>
                            <div>
                                <a href="mailto:admin@example.com" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-envelope"></i> Hubungi Admin
                                </a>
                                <a href="{{ route('publisher.validation.pending') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Status
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tokenInput = document.getElementById('validation_token');
    
    // Auto-uppercase and format token input
    tokenInput.addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 8) {
            value = value.substring(0, 8);
        }
        e.target.value = value;
    });

    // Auto-submit when 8 characters entered
    tokenInput.addEventListener('input', function(e) {
        if (e.target.value.length === 8) {
            // Add a small delay to allow user to see the complete token
            setTimeout(() => {
                if (e.target.value.length === 8) {
                    e.target.closest('form').submit();
                }
            }, 500);
        }
    });

    // Focus on input when page loads
    tokenInput.focus();
});
</script>
@endpush

@push('styles')
<style>
.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.alert {
    border: none;
    border-radius: 10px;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card {
    backdrop-filter: blur(10px);
}

.form-control-lg {
    font-size: 1.5rem;
    padding: 0.75rem 1rem;
}
</style>
@endpush
