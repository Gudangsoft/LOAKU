@extends('layouts.app')

@section('title', 'Cari LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-search me-2"></i>
                        Cari LOA
                    </h4>
                    <p class="mb-0 mt-2">Masukkan kode LOA atau email penulis untuk mencari LOA yang sudah disetujui</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('loa.find') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="search" class="form-label">
                                <i class="fas fa-key me-1"></i>
                                Kode LOA atau Email Penulis <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('search') is-invalid @enderror" 
                                       id="search" 
                                       name="search" 
                                       value="{{ old('search') }}" 
                                       placeholder="Contoh: LOA20250801030918 atau email@example.com"
                                       required>
                                @error('search')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan kode LOA (LOA20250801030918) atau email penulis untuk mencari LOA
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-search me-1"></i>
                                Cari LOA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-success">
                        <i class="fas fa-question-circle me-1"></i>
                        Bantuan Pencarian
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Pencarian dengan Kode LOA:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Format: LOA[Tanggal][Nomor]</li>
                                <li><i class="fas fa-check text-success me-2"></i>Contoh: LOA20250801030918</li>
                                <li><i class="fas fa-check text-success me-2"></i>Kode LOA diberikan setelah approval</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Pencarian dengan Email:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Gunakan email penulis</li>
                                <li><i class="fas fa-check text-success me-2"></i>Email yang digunakan saat request</li>
                                <li><i class="fas fa-check text-success me-2"></i>Hanya LOA yang sudah disetujui</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
