@extends('layouts.app')

@section('title', 'Verifikasi LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Verifikasi LOA
                    </h4>
                    <p class="mb-0 mt-2">Masukkan kode LOA untuk memverifikasi keaslian dokumen</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('loa.check') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="loa_code" class="form-label">
                                <i class="fas fa-key me-1"></i>
                                Kode LOA <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('loa_code') is-invalid @enderror" 
                                       id="loa_code" 
                                       name="loa_code" 
                                       value="{{ old('loa_code') }}" 
                                       placeholder="Contoh: LOA20250801001"
                                       required>
                                @error('loa_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan kode LOA yang ingin diverifikasi (format: LOA[Tanggal][Nomor])
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-shield-alt me-1"></i>
                                Verifikasi LOA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-info-circle me-1"></i>
                        Tentang Verifikasi LOA
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Fungsi Verifikasi:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Memastikan keaslian LOA</li>
                                <li><i class="fas fa-check text-success me-2"></i>Mencegah pemalsuan dokumen</li>
                                <li><i class="fas fa-check text-success me-2"></i>Validasi data artikel</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Hasil Verifikasi:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Status validitas LOA</li>
                                <li><i class="fas fa-check text-success me-2"></i>Detail informasi artikel</li>
                                <li><i class="fas fa-check text-success me-2"></i>Tanggal approval resmi</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tips:</strong> Kode LOA yang valid akan menampilkan informasi lengkap artikel dan status "Terverifikasi". 
                        Jika LOA tidak ditemukan atau belum divalidasi, sistem akan memberikan pesan error.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
