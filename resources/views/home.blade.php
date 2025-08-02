@extends('layouts.app')

@section('title', 'Home - LOA Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-4">
                    <i class="fas fa-certificate me-3"></i>
                    LOA Management System
                </h1>
                <p class="lead mb-4">
                    Sistem manajemen pengelolaan Letter of Acceptance (LOA) untuk artikel jurnal ilmiah. 
                    Mudah, cepat, dan terpercaya untuk mengelola proses persetujuan publikasi artikel Anda.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('loa.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        Request LOA
                    </a>
                    <a href="{{ route('loa.search') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>
                        Cari LOA
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-file-certificate" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Statistik Sistem</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card stats-card text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-file-alt fa-3x mb-3"></i>
                        <h3>{{ $totalRequests }}</h3>
                        <p class="mb-0">Total Permintaan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h3>{{ $approvedRequests }}</h3>
                        <p class="mb-0">LOA Disetujui</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x mb-3"></i>
                        <h3>{{ $pendingRequests }}</h3>
                        <p class="mb-0">Menunggu Validasi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <h3>{{ $totalJournals }}</h3>
                        <p class="mb-0">Jurnal Aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Fitur Utama</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Request LOA</h5>
                        <p class="card-text">
                            Ajukan permintaan Letter of Acceptance dengan mudah melalui form online yang lengkap dan user-friendly.
                        </p>
                        <a href="{{ route('loa.create') }}" class="btn btn-primary">
                            Mulai Request
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-search fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Cari LOA</h5>
                        <p class="card-text">
                            Cari dan download LOA yang sudah disetujui menggunakan kode LOA atau email penulis dengan mudah.
                        </p>
                        <a href="{{ route('loa.search') }}" class="btn btn-success">
                            Cari Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-qrcode fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Scan QR Code</h5>
                        <p class="card-text">
                            Verifikasi LOA dengan mudah menggunakan QR Code scanner. Scan langsung tanpa perlu mengetik kode manual.
                        </p>
                        <a href="{{ route('qr.scanner') }}" class="btn btn-info">
                            Scan QR Code
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Verifikasi LOA</h5>
                        <p class="card-text">
                            Verifikasi keaslian LOA dengan memasukkan kode LOA untuk memastikan validitas dan keaslian dokumen.
                        </p>
                        <a href="{{ route('loa.verify') }}" class="btn btn-warning">
                            Verifikasi LOA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Cara Kerja</h2>
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">1</span>
                    </div>
                </div>
                <h5 class="mt-3">Submit Request</h5>
                <p>Isi form permintaan LOA dengan data artikel dan jurnal yang lengkap</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">2</span>
                    </div>
                </div>
                <h5 class="mt-3">Admin Review</h5>
                <p>Tim admin akan mereview dan memvalidasi permintaan LOA Anda</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">3</span>
                    </div>
                </div>
                <h5 class="mt-3">LOA Generated</h5>
                <p>LOA akan di-generate otomatis dengan kode unik setelah disetujui</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">4</span>
                    </div>
                </div>
                <h5 class="mt-3">Download & Print</h5>
                <p>Download LOA dalam format PDF bilingual (Indonesia & English)</p>
            </div>
        </div>
    </div>
</section>

<!-- Publishers Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Publisher Terdaftar</h2>
        <div class="row">
            @if($publishers->count() > 0)
                @foreach($publishers as $publisher)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($publisher->logo)
                        <div class="text-center p-3">
                            <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                 alt="{{ $publisher->name }}" 
                                 class="img-fluid" 
                                 style="max-height: 80px; object-fit: contain;">
                        </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $publisher->name }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ Str::limit($publisher->address, 50) }}
                            </p>
                            @if($publisher->phone)
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-phone me-2"></i>
                                {{ $publisher->phone }}
                            </p>
                            @endif
                            @if($publisher->email)
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <a href="mailto:{{ $publisher->email }}" class="text-decoration-none">
                                    {{ $publisher->email }}
                                </a>
                            </p>
                            @endif
                            @if($publisher->website)
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-globe me-2"></i>
                                <a href="{{ $publisher->website }}" target="_blank" class="text-decoration-none">
                                    Website
                                </a>
                            </p>
                            @endif
                            @if($publisher->whatsapp)
                            <p class="card-text text-muted">
                                <i class="fab fa-whatsapp me-2"></i>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $publisher->whatsapp) }}" 
                                   target="_blank" class="text-decoration-none text-success">
                                    WhatsApp
                                </a>
                            </p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="fas fa-journal-whills me-1"></i>
                                {{ $publisher->journals->count() }} Jurnal Terdaftar
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada publisher yang terdaftar
                    </div>
                </div>
            @endif
        </div>
        
        @if($totalPublishers > 6)
        <div class="text-center mt-4">
            <a href="/publishers" class="btn btn-outline-primary">
                <i class="fas fa-plus me-2"></i>Lihat Semua Publisher
            </a>
        </div>
        @endif
    </div>
</section>

@endsection
