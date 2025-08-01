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
                        LOA Disetujui
                    </h4>
                    <p class="mb-0 mt-2">Permohonan LOA telah disetujui dan sedang dalam proses finalisasi</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Verification Status -->
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-thumbs-up fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Permohonan Disetujui!</h5>
                            <p class="mb-0">LOA dengan nomor registrasi <strong>{{ $loaRequest->no_reg }}</strong> telah disetujui dan sedang dalam tahap finalisasi.</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Detail Artikel</h5>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-id-card me-2"></i>No. Registrasi:</strong><br>
                                        <span class="badge bg-success fs-6">{{ $loaRequest->no_reg }}</span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-calendar me-2"></i>Tanggal Pengajuan:</strong><br>
                                        {{ $loaRequest->created_at->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <p class="mb-3">
                                <strong><i class="fas fa-file-alt me-2"></i>Judul Artikel:</strong><br>
                                {{ $loaRequest->article_title }}
                            </p>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-user me-2"></i>Penulis:</strong><br>
                                        {{ $loaRequest->author }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                                        {{ $loaRequest->email }}
                                    </p>
                                </div>
                            </div>
                            
                            <p class="mb-3">
                                <strong><i class="fas fa-university me-2"></i>Afiliasi:</strong><br>
                                {{ $loaRequest->affiliation }}
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Status Permohonan
                                    </h6>
                                    <div class="mb-3">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            DISETUJUI
                                        </span>
                                    </div>
                                    
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-book me-1"></i>
                                        Detail Jurnal
                                    </h6>
                                    @if($loaRequest->journal)
                                        <p class="mb-1"><strong>Jurnal:</strong><br>{{ $loaRequest->journal->name }}</p>
                                        <p class="mb-1"><strong>ISSN:</strong><br>{{ $loaRequest->journal->issn }}</p>
                                        @if($loaRequest->journal->publisher)
                                            <p class="mb-0"><strong>Penerbit:</strong><br>{{ $loaRequest->journal->publisher->name }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-clock me-1"></i>
                        Tahap Selanjutnya
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Proses Finalisasi:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-hourglass-half text-warning me-2"></i>Pembuatan sertifikat LOA</li>
                                <li><i class="fas fa-hourglass-half text-warning me-2"></i>Verifikasi data final</li>
                                <li><i class="fas fa-hourglass-half text-warning me-2"></i>Penerbitan dokumen resmi</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Estimasi Waktu:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clock text-info me-2"></i>1-3 hari kerja</li>
                                <li><i class="fas fa-envelope text-info me-2"></i>Notifikasi via email</li>
                                <li><i class="fas fa-download text-info me-2"></i>Download tersedia setelah selesai</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Catatan:</strong> Anda akan menerima notifikasi email ketika LOA resmi telah selesai dan siap untuk diunduh. 
                        Dokumen LOA final akan tersedia dalam format PDF bilingual (Indonesia & Inggris).
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('loa.verify') }}" class="btn btn-warning me-2">
                    <i class="fas fa-shield-alt me-1"></i>
                    Verifikasi LOA Lain
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
