@extends('layouts.app')

@section('title', 'Request LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Form Request LOA
                    </h4>
                    <p class="mb-0 mt-2">Silakan isi semua field dengan lengkap dan benar</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('loa.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Article ID -->
                            <div class="col-md-6 mb-3">
                                <label for="article_id" class="form-label">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID Artikel <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('article_id') is-invalid @enderror" 
                                       id="article_id" 
                                       name="article_id" 
                                       value="{{ old('article_id') }}" 
                                       placeholder="Masukkan ID Artikel"
                                       required>
                                @error('article_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Volume -->
                            <div class="col-md-3 mb-3">
                                <label for="volume" class="form-label">
                                    <i class="fas fa-book me-1"></i>
                                    Volume <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('volume') is-invalid @enderror" 
                                       id="volume" 
                                       name="volume" 
                                       value="{{ old('volume') }}" 
                                       min="1"
                                       placeholder="1"
                                       required>
                                @error('volume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Number -->
                            <div class="col-md-3 mb-3">
                                <label for="number" class="form-label">
                                    <i class="fas fa-list-ol me-1"></i>
                                    Nomor <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('number') is-invalid @enderror" 
                                       id="number" 
                                       name="number" 
                                       value="{{ old('number') }}" 
                                       min="1"
                                       placeholder="1"
                                       required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Month -->
                            <div class="col-md-6 mb-3">
                                <label for="month" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    Bulan <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('month') is-invalid @enderror" 
                                        id="month" 
                                        name="month" 
                                        required>
                                    <option value="">Pilih Bulan</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Year -->
                            <div class="col-md-6 mb-3">
                                <label for="year" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Tahun <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('year') is-invalid @enderror" 
                                        id="year" 
                                        name="year" 
                                        required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ old('year') == $year || $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Article Title -->
                        <div class="mb-3">
                            <label for="article_title" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('article_title') is-invalid @enderror" 
                                      id="article_title" 
                                      name="article_title" 
                                      rows="3" 
                                      placeholder="Masukkan Judul Artikel"
                                      required>{{ old('article_title') }}</textarea>
                            @error('article_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <!-- Author -->
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Penulis <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('author') is-invalid @enderror" 
                                       id="author" 
                                       name="author" 
                                       value="{{ old('author') }}" 
                                       placeholder="Nama Penulis"
                                       required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Author Email -->
                            <div class="col-md-6 mb-3">
                                <label for="author_email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email Penulis <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('author_email') is-invalid @enderror" 
                                       id="author_email" 
                                       name="author_email" 
                                       value="{{ old('author_email') }}" 
                                       placeholder="email@example.com"
                                       required>
                                @error('author_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Journal -->
                        <div class="mb-4">
                            <label for="journal_id" class="form-label">
                                <i class="fas fa-journal-whills me-1"></i>
                                Jurnal <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('journal_id') is-invalid @enderror" 
                                    id="journal_id" 
                                    name="journal_id" 
                                    required>
                                <option value="">-- Pilih Jurnal --</option>
                                @if($journals->count() > 0)
                                    @foreach($journals as $journal)
                                        <option value="{{ $journal->id }}" {{ old('journal_id') == $journal->id ? 'selected' : '' }}>
                                            {{ $journal->name }} 
                                            @if($journal->e_issn)
                                                (e-ISSN: {{ $journal->e_issn }})
                                            @endif
                                            - {{ $journal->publisher->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada jurnal tersedia</option>
                                @endif
                            </select>
                            @error('journal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>
                                Submit Request LOA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        Informasi Penting
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Semua field wajib diisi dengan benar</li>
                        <li><i class="fas fa-check text-success me-2"></i>Nomor registrasi akan dibuat otomatis dengan format: LOASIP.{ID_Artikel}.{Nomor_Urut}</li>
                        <li><i class="fas fa-check text-success me-2"></i>Anda akan menerima notifikasi setelah admin memvalidasi permintaan</li>
                        <li><i class="fas fa-check text-success me-2"></i>LOA dapat diunduh setelah disetujui melalui menu "Cari LOA"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
