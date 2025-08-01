@extends('layouts.app')

@section('title', 'Hasil Pencarian LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- LOA Found Card -->
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        LOA Ditemukan
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">{{ $loaValidated->loaRequest->article_title }}</h5>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-hashtag me-2"></i>Kode LOA:</strong><br>
                                        <span class="badge bg-primary fs-6">{{ $loaValidated->loa_code }}</span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-id-card me-2"></i>No. Registrasi:</strong><br>
                                        {{ $loaValidated->loaRequest->no_reg }}
                                    </p>
                                </div>
                            </div>
                            
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
                            
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar me-2"></i>Edisi:</strong><br>
                                {{ $loaValidated->loaRequest->edition }}
                            </p>
                            
                            <p class="mb-0">
                                <strong><i class="fas fa-calendar-check me-2"></i>Tanggal Approval:</strong><br>
                                {{ $loaValidated->loaRequest->approved_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-certificate text-success" style="font-size: 80px;"></i>
                                <h6 class="mt-2 text-success">LOA Tervalidasi</h6>
                                <small class="text-muted">Dokumen resmi dan sah</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-download me-2"></i>
                        Download & Aksi
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Print PDF Indonesian -->
                        <div class="col-md-3">
                            <a href="{{ route('loa.print', [$loaValidated->loa_code, 'id']) }}" 
                               class="btn btn-primary w-100" 
                               target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>
                                PDF Indonesia
                            </a>
                        </div>
                        
                        <!-- Print PDF English -->
                        <div class="col-md-3">
                            <a href="{{ route('loa.print', [$loaValidated->loa_code, 'en']) }}" 
                               class="btn btn-info w-100" 
                               target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>
                                PDF English
                            </a>
                        </div>
                        
                        <!-- View Indonesian -->
                        <div class="col-md-3">
                            <a href="{{ route('loa.view', [$loaValidated->loa_code, 'id']) }}" 
                               class="btn btn-success w-100" 
                               target="_blank">
                                <i class="fas fa-eye me-2"></i>
                                View Indonesia
                            </a>
                        </div>
                        
                        <!-- View English -->
                        <div class="col-md-3">
                            <a href="{{ route('loa.view', [$loaValidated->loa_code, 'en']) }}" 
                               class="btn btn-warning w-100" 
                               target="_blank">
                                <i class="fas fa-eye me-2"></i>
                                View English
                            </a>
                        </div>
                    </div>
                    
                    @if($loaValidated->loaRequest->journal->website)
                    <div class="mt-3">
                        <a href="{{ $loaValidated->loaRequest->journal->website }}" 
                           class="btn btn-outline-primary" 
                           target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Visit Journal Website
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('loa.search') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Cari LOA Lain
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
