@extends('layouts.app')

@section('title', 'Test Verifikasi LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-vial me-2"></i>
                        Test Verifikasi LOA
                    </h4>
                    <p class="mb-0 mt-2">Kode LOA test untuk mencoba fitur verifikasi</p>
                </div>
                
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Gunakan kode LOA berikut untuk mencoba fitur verifikasi:
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-success">Test LOA #1</h6>
                                    <code class="fs-5">LOA20250801001</code>
                                    <p class="card-text mt-2 small">Machine Learning Research</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-warning">Test LOA #2</h6>
                                    <code class="fs-5">LOA20250801002</code>
                                    <p class="card-text mt-2 small">AI Applications Study</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-primary">Test LOA #3</h6>
                                    <code class="fs-5">LOA20250801003</code>
                                    <p class="card-text mt-2 small">Sustainable Technology</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('loa.verify') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-shield-alt me-2"></i>
                            Mulai Verifikasi LOA
                        </a>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="fas fa-question-circle me-1"></i>
                        Cara Menggunakan
                    </h6>
                    <ol>
                        <li>Pilih salah satu kode LOA di atas</li>
                        <li>Klik tombol "Mulai Verifikasi LOA"</li>
                        <li>Masukkan kode LOA yang dipilih</li>
                        <li>Klik "Verifikasi LOA" untuk melihat hasilnya</li>
                    </ol>
                    
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Hasil yang Diharapkan:</strong> Jika kode LOA valid, sistem akan menampilkan detail artikel, informasi penulis, jurnal, dan status verifikasi "Terverifikasi".
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
