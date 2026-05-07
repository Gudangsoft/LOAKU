@extends('layouts.app')

@section('title', 'Request LOA - LOA SIPTENAN')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 60%, #1E40AF 100%);
        color: white;
        padding: 48px 0 40px;
        margin-bottom: -40px;
    }

    .page-header h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 6px; }
    .page-header p  { color: #BAE6FD; font-size: 0.9rem; margin: 0; }

    .form-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .form-card-header {
        background: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%);
        padding: 24px 32px;
        color: white;
    }

    .form-card-header h4 { font-weight: 700; font-size: 1.1rem; margin: 0; }

    .form-card-body { padding: 32px; }

    .form-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #4F46E5;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #EEF2FF;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: #1E293B;
        transition: border-color .2s, box-shadow .2s;
        background-color: #FAFAFA;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        background-color: white;
    }

    .form-control::placeholder { color: #94A3B8; }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #EF4444;
        background-color: #FFF5F5;
    }

    .invalid-feedback { font-size: 0.8rem; color: #EF4444; margin-top: 5px; }

    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .form-control {
        padding-left: 40px;
    }

    .input-icon-wrap .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .required-star { color: #EF4444; }

    .sidebar-sticky {
        position: sticky;
        top: 80px;
    }

    .info-card {
        background: #F0F9FF;
        border: 1px solid #BAE6FD;
        border-radius: 16px;
        padding: 24px;
    }

    .info-card h6 {
        font-size: 0.875rem;
        font-weight: 700;
        color: #0369A1;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.85rem;
        color: #0C4A6E;
        line-height: 1.5;
    }

    .info-item i { color: #0EA5E9; margin-top: 2px; flex-shrink: 0; }

    .steps-mini { margin-top: 24px; }

    .step-mini {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
    }

    .step-mini-num {
        width: 24px; height: 24px;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        color: white;
        border-radius: 50%;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .step-mini-text { font-size: 0.8rem; color: #475569; line-height: 1.5; }
    .step-mini-text strong { color: #1E293B; }

    .form-footer {
        background: #F8FAFC;
        padding: 20px 32px;
        border-top: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-cancel {
        background: white;
        border: 1.5px solid #E2E8F0;
        color: #64748B;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .btn-cancel:hover { border-color: #CBD5E1; color: #1E293B; background: #F1F5F9; }

    .btn-submit {
        background: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 10px 28px;
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(79,70,229,.35); }

    .journal-option-meta {
        display: block;
        font-size: 0.8rem;
        color: #94A3B8;
    }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:12px">
            <ol class="breadcrumb mb-0" style="font-size:.8rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#BAE6FD;text-decoration:none">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:#E0E7FF">Request LOA</li>
            </ol>
        </nav>
        <h1><i class="fas fa-paper-plane me-3" style="font-size:1.4rem"></i>Form Request LOA</h1>
        <p>Lengkapi formulir berikut untuk mengajukan permintaan Letter of Acceptance.</p>
    </div>
</div>

<div class="container py-5" style="padding-top:60px!important">
    <div class="row g-4 justify-content-center">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-card-header">
                    <h4><i class="fas fa-file-alt me-2"></i>Data Permintaan LOA</h4>
                    <p class="mb-0 mt-1" style="font-size:.85rem;color:rgba(255,255,255,.75)">Semua field bertanda <span style="color:#FCA5A5">*</span> wajib diisi</p>
                </div>

                <div class="form-card-body">
                    <form action="{{ route('loa.store') }}" method="POST" id="loaForm">
                        @csrf

                        <!-- Informasi Artikel -->
                        <div class="form-section-title">
                            <i class="fas fa-file-alt"></i> Informasi Artikel
                        </div>

                        <div class="mb-3">
                            <label for="article_title" class="form-label">
                                Judul Artikel <span class="required-star">*</span>
                            </label>
                            <textarea class="form-control @error('article_title') is-invalid @enderror"
                                      id="article_title"
                                      name="article_title"
                                      rows="3"
                                      placeholder="Tuliskan judul artikel Anda secara lengkap"
                                      required>{{ old('article_title') }}</textarea>
                            @error('article_title')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="article_id" class="form-label">
                                    ID Artikel <span class="required-star">*</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-hashtag input-icon"></i>
                                    <input type="text"
                                           class="form-control @error('article_id') is-invalid @enderror"
                                           id="article_id"
                                           name="article_id"
                                           value="{{ old('article_id') }}"
                                           placeholder="Contoh: ART-2025-001"
                                           required>
                                </div>
                                @error('article_id')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="volume" class="form-label">
                                    Volume <span class="required-star">*</span>
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
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="number" class="form-label">
                                    Nomor <span class="required-star">*</span>
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
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-0">
                            <div class="col-md-6">
                                <label for="month" class="form-label">
                                    Bulan Penerbitan <span class="required-star">*</span>
                                </label>
                                <select class="form-select @error('month') is-invalid @enderror"
                                        id="month"
                                        name="month"
                                        required>
                                    <option value="">— Pilih Bulan —</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="year" class="form-label">
                                    Tahun Penerbitan <span class="required-star">*</span>
                                </label>
                                <select class="form-select @error('year') is-invalid @enderror"
                                        id="year"
                                        name="year"
                                        required>
                                    <option value="">— Pilih Tahun —</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ old('year') == $year || $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Penulis -->
                        <div class="form-section-title mt-4">
                            <i class="fas fa-user-edit"></i> Informasi Penulis
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="author" class="form-label">
                                    Nama Penulis <span class="required-star">*</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text"
                                           class="form-control @error('author') is-invalid @enderror"
                                           id="author"
                                           name="author"
                                           value="{{ old('author') }}"
                                           placeholder="Nama lengkap penulis utama"
                                           required>
                                </div>
                                @error('author')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="author_email" class="form-label">
                                    Email Penulis <span class="required-star">*</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email"
                                           class="form-control @error('author_email') is-invalid @enderror"
                                           id="author_email"
                                           name="author_email"
                                           value="{{ old('author_email') }}"
                                           placeholder="email@institusi.ac.id"
                                           required>
                                </div>
                                @error('author_email')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pilih Jurnal -->
                        <div class="form-section-title mt-4">
                            <i class="fas fa-book-open"></i> Jurnal Tujuan
                        </div>

                        <div class="mb-2">
                            <label for="journal_id" class="form-label">
                                Pilih Jurnal <span class="required-star">*</span>
                            </label>
                            <select class="form-select @error('journal_id') is-invalid @enderror"
                                    id="journal_id"
                                    name="journal_id"
                                    required>
                                <option value="">— Pilih Jurnal Tujuan —</option>
                                @if($journals->count() > 0)
                                    @foreach($journals as $journal)
                                        <option value="{{ $journal->id }}" {{ old('journal_id') == $journal->id ? 'selected' : '' }}>
                                            {{ $journal->name }}@if($journal->e_issn) (e-ISSN: {{ $journal->e_issn }})@endif — {{ $journal->publisher->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada jurnal tersedia</option>
                                @endif
                            </select>
                            @error('journal_id')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                            <div style="font-size:.78rem;color:#64748B;margin-top:5px">
                                <i class="fas fa-info-circle me-1" style="color:#06B6D4"></i>
                                Pilih jurnal yang sesuai dengan topik artikel Anda
                            </div>
                        </div>

                    </form>
                </div>

                <div class="form-footer">
                    <a href="{{ route('home') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" form="loaForm" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Permintaan LOA
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="sidebar-sticky">
            <div class="info-card">
                <h6><i class="fas fa-info-circle"></i> Panduan Pengisian</h6>
                <div class="info-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Isi semua field yang bertanda <strong style="color:#EF4444">*</strong> dengan benar dan lengkap</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-id-badge"></i>
                    <span>Kode LOA format: <strong>LOASIP.{ID}.{Urutan}</strong> dibuat otomatis</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span>Gunakan email aktif untuk menerima notifikasi status LOA</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-download"></i>
                    <span>LOA dapat diunduh melalui menu <strong>Cari LOA</strong> setelah disetujui</span>
                </div>

                <div class="steps-mini">
                    <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#0369A1;margin-bottom:12px">
                        <i class="fas fa-route me-1"></i> Alur Proses
                    </div>
                    <div class="step-mini">
                        <div class="step-mini-num">1</div>
                        <div class="step-mini-text"><strong>Submit form</strong> ini dengan data lengkap</div>
                    </div>
                    <div class="step-mini">
                        <div class="step-mini-num">2</div>
                        <div class="step-mini-text"><strong>Admin mereview</strong> dan memvalidasi data</div>
                    </div>
                    <div class="step-mini">
                        <div class="step-mini-num">3</div>
                        <div class="step-mini-text"><strong>LOA diterbitkan</strong> dengan QR Code unik</div>
                    </div>
                    <div class="step-mini">
                        <div class="step-mini-num">4</div>
                        <div class="step-mini-text"><strong>Download PDF</strong> dari menu Cari LOA</div>
                    </div>
                </div>
            </div>

            <!-- Quick links -->
            <div style="background:white;border:1px solid #E2E8F0;border-radius:16px;padding:20px;margin-top:16px">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748B;margin-bottom:14px">
                    Akses Cepat
                </div>
                <a href="{{ route('loa.search') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500;margin-bottom:4px"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#ECFDF5;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#059669;font-size:.85rem">
                        <i class="fas fa-search"></i>
                    </div>
                    Cari LOA yang sudah ada
                </a>
                <a href="{{ route('loa.verify') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500;margin-bottom:4px"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#FFFBEB;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#D97706;font-size:.85rem">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    Verifikasi keaslian LOA
                </a>
                <a href="{{ route('qr.scanner') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#ECFEFF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0891B2;font-size:.85rem">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    Scan QR Code LOA
                </a>
            </div>
            </div>{{-- /sidebar-sticky --}}
        </div>
    </div>
</div>
@endsection
