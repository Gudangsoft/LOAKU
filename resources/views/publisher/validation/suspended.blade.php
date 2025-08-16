@extends('layouts.app')

@section('title', 'Publisher Account - Suspended')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5 text-center">
                        <!-- Status Icon -->
                        <div class="mb-4">
                            <i class="fas fa-ban text-danger" style="font-size: 4rem;"></i>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="h3 mb-3 text-dark">Akun Publisher Disuspend</h1>
                        <p class="text-muted mb-4">
                            Akun Publisher Anda telah disuspend oleh administrator. 
                            Akses ke fitur Publisher sementara tidak tersedia.
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

                        <!-- Suspension Details -->
                        @if($publisher->validated_at)
                        <div class="bg-danger bg-opacity-10 rounded p-3 mb-4">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-calendar-alt"></i> Tanggal Suspend
                            </h6>
                            <p class="mb-1">{{ $publisher->validated_at->format('d M Y H:i') }}</p>
                            <small class="text-muted">{{ $publisher->validated_at->diffForHumans() }}</small>
                            
                            @if($publisher->validator)
                            <hr>
                            <p class="mb-0">
                                <strong>Oleh:</strong> {{ $publisher->validator->name }}
                            </p>
                            @endif
                        </div>
                        @endif

                        <!-- Suspension Reason -->
                        @if($publisher && $publisher->validation_notes)
                        <div class="alert alert-warning text-start">
                            <h6><i class="fas fa-exclamation-triangle"></i> Alasan Suspend:</h6>
                            <p class="mb-0">{{ $publisher->validation_notes }}</p>
                        </div>
                        @endif
                        @endif

                        <!-- Status Information -->
                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <div class="bg-success rounded p-3 text-white">
                                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                                    <h6>Pendaftaran</h6>
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-success rounded p-3 text-white">
                                    <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                    <h6>Validasi</h6>
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-danger rounded p-3 text-white">
                                    <i class="fas fa-ban fa-2x mb-2"></i>
                                    <h6>Status</h6>
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="alert alert-info text-start">
                            <h6><i class="fas fa-info-circle"></i> Langkah Selanjutnya:</h6>
                            <ol class="mb-0">
                                <li>Hubungi administrator untuk klarifikasi alasan suspend</li>
                                <li>Perbaiki masalah atau kelengkapan yang diminta</li>
                                <li>Tunggu proses reaktivasi dari administrator</li>
                                <li>Akses akan dikembalikan setelah masalah teratasi</li>
                            </ol>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-light rounded p-3 mb-4">
                            <h6><i class="fas fa-headset"></i> Pusat Bantuan</h6>
                            <div class="row">
                                <div class="col-6">
                                    <a href="mailto:admin@example.com" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-envelope"></i> Email Admin
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="tel:+628123456789" class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-phone"></i> Call Center
                                    </a>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Jam kerja: Senin - Jumat, 08:00 - 17:00 WIB
                            </small>
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
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.bg-danger.bg-opacity-10 {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@endpush
