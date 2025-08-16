@extends('layouts.app')

@section('title', 'Publisher Validation - Pending')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5 text-center">
                        <!-- Status Icon -->
                        <div class="mb-4">
                            <i class="fas fa-clock text-warning" style="font-size: 4rem;"></i>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="h3 mb-3 text-dark">Akun Publisher Sedang Divalidasi</h1>
                        <p class="text-muted mb-4">
                            Terima kasih telah mendaftar sebagai Publisher. 
                            Akun Anda sedang dalam proses validasi oleh administrator.
                        </p>

                        <!-- Publisher Info -->
                        @if($publisher)
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center">
                                @if($publisher->logo)
                                    <img src="{{ Storage::url($publisher->logo) }}" 
                                         alt="{{ $publisher->name }}" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-building text-white"></i>
                                    </div>
                                @endif
                                <div class="text-start">
                                    <h5 class="mb-1">{{ $publisher->name }}</h5>
                                    <small class="text-muted">{{ $publisher->type ?? 'Publisher' }}</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Status Information -->
                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <div class="bg-primary rounded p-3 text-white">
                                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                                    <h6>Pendaftaran</h6>
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-warning rounded p-3 text-dark">
                                    <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                    <h6>Validasi</h6>
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-3 text-muted">
                                    <i class="fas fa-key fa-2x mb-2"></i>
                                    <h6>Aktivasi</h6>
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="alert alert-info text-start">
                            <h6><i class="fas fa-info-circle"></i> Proses Selanjutnya:</h6>
                            <ol class="mb-0">
                                <li>Administrator akan memvalidasi dokumen dan informasi yang Anda berikan</li>
                                <li>Setelah disetujui, Anda akan menerima <strong>token aktivasi</strong></li>
                                <li>Masukkan token tersebut untuk mengaktifkan akun Publisher</li>
                                <li>Akses penuh ke sistem Publisher akan tersedia setelah aktivasi</li>
                            </ol>
                        </div>

                        <!-- Token Status -->
                        @if($publisher && $publisher->validation_token)
                        <div class="alert alert-success text-start">
                            <h6><i class="fas fa-key"></i> Token Validasi Tersedia!</h6>
                            <p class="mb-2">Administrator telah menyetujui akun Anda. Silakan masukkan token berikut untuk aktivasi:</p>
                            <div class="d-flex align-items-center">
                                <code class="bg-dark text-light px-3 py-2 rounded me-2 flex-grow-1">{{ $publisher->validation_token }}</code>
                                <a href="{{ route('publisher.validation.token') }}" class="btn btn-success">
                                    <i class="fas fa-unlock"></i> Aktivasi Sekarang
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Contact Information -->
                        <div class="text-muted">
                            <p class="mb-1">
                                <i class="fas fa-envelope"></i> 
                                Butuh bantuan? Hubungi: <a href="mailto:admin@example.com">admin@example.com</a>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-clock"></i>
                                Proses validasi biasanya memakan waktu 1-2 hari kerja.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Home
                            </a>
                            <button onclick="location.reload()" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Refresh Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline-step {
    transition: all 0.3s ease;
}

.timeline-step.completed {
    background: #28a745 !important;
    color: white !important;
}

.timeline-step.active {
    background: #ffc107 !important;
    color: #333 !important;
}

.card {
    backdrop-filter: blur(10px);
}

.bg-light {
    background-color: #f8f9fa !important;
}

.alert {
    border: none;
    border-radius: 10px;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
}
</style>
@endpush
