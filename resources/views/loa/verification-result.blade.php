@extends('layouts.app')

@section('title', 'Hasil Verifikasi LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Verification Success Card -->
            <div class="card shadow-lg border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        LOA Terverifikasi
                    </h4>
                    <p class="mb-0 mt-2">Dokumen LOA ini adalah valid dan resmi</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Verification Status -->
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-shield-check fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Verifikasi Berhasil!</h5>
                            <p class="mb-0">LOA dengan kode <strong>{{ $loaValidated->loa_code }}</strong> adalah dokumen resmi dan telah tervalidasi.</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Detail Artikel</h5>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-hashtag me-2"></i>Kode LOA:</strong><br>
                                        <span class="badge bg-success fs-6">{{ $loaValidated->loa_code }}</span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-id-card me-2"></i>No. Registrasi:</strong><br>
                                        {{ $loaValidated->loaRequest->no_reg }}
                                    </p>
                                </div>
                            </div>
                            
                            <p class="mb-3">
                                <strong><i class="fas fa-file-alt me-2"></i>Judul Artikel:</strong><br>
                                {{ $loaValidated->loaRequest->article_title }}
                            </p>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-user me-2"></i>Penulis:</strong><br>
                                        {{ $loaValidated->loaRequest->author }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                                        {{ $loaValidated->loaRequest->author_email }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-journal-whills me-2"></i>Jurnal:</strong><br>
                                        {{ $loaValidated->loaRequest->journal->name }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-building me-2"></i>Penerbit:</strong><br>
                                        {{ $loaValidated->loaRequest->journal->publisher->name }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-calendar me-2"></i>Edisi:</strong><br>
                                        {{ $loaValidated->loaRequest->edition }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-calendar-check me-2"></i>Tanggal Validasi:</strong><br>
                                        {{ $loaValidated->created_at->format('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="border border-success rounded p-3 bg-light">
                                <i class="fas fa-certificate text-success" style="font-size: 80px;"></i>
                                <h6 class="mt-2 text-success">TERVERIFIKASI</h6>
                                <small class="text-muted">Dokumen Valid & Resmi</small>
                                
                                <div class="mt-3">
                                    <div class="badge bg-success mb-1">Authentic</div><br>
                                    <div class="badge bg-primary mb-1">Approved</div><br>
                                    <div class="badge bg-warning">Official</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Verification Details -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Verifikasi
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Status Verifikasi:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>LOA Valid dan Terverifikasi</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Dokumen Resmi dan Sah</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Telah Disetujui Admin</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Waktu Verifikasi:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clock text-info me-2"></i>Tanggal: {{ now()->format('d F Y') }}</li>
                                <li><i class="fas fa-clock text-info me-2"></i>Waktu: {{ now()->format('H:i:s') }}</li>
                                <li><i class="fas fa-globe text-info me-2"></i>Sistem: LOA Management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('loa.verify') }}" class="btn btn-warning">
                    <i class="fas fa-shield-alt me-2"></i>
                    Verifikasi LOA Lain
                </a>
                <a href="{{ route('loa.search') }}" class="btn btn-success ms-2">
                    <i class="fas fa-search me-2"></i>
                    Cari & Download LOA
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-home me-2"></i>
                    Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
