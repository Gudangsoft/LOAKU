@extends('layouts.app')

@section('title', 'Cari LOA - LOA SIPTENAN')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #064E3B 0%, #065F46 50%, #0F766E 100%);
        color: white;
        padding: 48px 0 40px;
        margin-bottom: -40px;
    }

    .page-header h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 6px; }
    .page-header p  { color: #A7F3D0; font-size: 0.9rem; margin: 0; }

    .search-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .search-card-header {
        background: linear-gradient(135deg, #059669 0%, #0891B2 100%);
        padding: 24px 32px;
        color: white;
    }

    .search-card-header h4 { font-weight: 700; font-size: 1.1rem; margin: 0; }

    .search-card-body { padding: 32px; }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap .search-input {
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 18px 14px 50px;
        font-size: 1rem;
        color: #1E293B;
        width: 100%;
        transition: border-color .2s, box-shadow .2s;
        background: #FAFAFA;
    }

    .search-input-wrap .search-input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5,150,105,.12);
        background: white;
        outline: none;
    }

    .search-input-wrap .search-input.is-invalid {
        border-color: #EF4444;
        background: #FFF5F5;
    }

    .search-input-wrap .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 1rem;
        pointer-events: none;
    }

    .invalid-feedback { font-size: 0.8rem; color: #EF4444; margin-top: 5px; }

    .search-hint {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        color: #64748B;
        margin-top: 8px;
    }

    .search-hint code {
        background: #F1F5F9;
        color: #0891B2;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.78rem;
    }

    .search-footer {
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

    .btn-search {
        background: linear-gradient(135deg, #059669 0%, #0891B2 100%);
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

    .btn-search:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(5,150,105,.35); }

    /* Help card */
    .help-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 24px;
        margin-top: 20px;
    }

    .help-card-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .help-method {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .help-method:last-child { margin-bottom: 0; }

    .help-method-title {
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .help-method-title.code { color: #0891B2; }
    .help-method-title.email { color: #D97706; }

    .help-method ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-method ul li {
        font-size: 0.8rem;
        color: #64748B;
        padding: 3px 0;
        display: flex;
        align-items: flex-start;
        gap: 7px;
    }

    .help-method ul li::before {
        content: '•';
        color: #059669;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* Sidebar */
    .sidebar-card {
        background: #F0FDF4;
        border: 1px solid #A7F3D0;
        border-radius: 16px;
        padding: 24px;
        position: sticky;
        top: 80px;
    }

    .sidebar-card h6 {
        font-size: 0.875rem;
        font-weight: 700;
        color: #065F46;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-tip {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.85rem;
        color: #065F46;
        line-height: 1.5;
    }

    .sidebar-tip i { color: #10B981; margin-top: 2px; flex-shrink: 0; }

    .example-code {
        background: #065F46;
        color: #A7F3D0;
        border-radius: 10px;
        padding: 12px 16px;
        font-family: monospace;
        font-size: 0.85rem;
        margin-top: 16px;
        letter-spacing: .3px;
    }

    .example-code .label {
        font-size: 0.7rem;
        color: #6EE7B7;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:12px">
            <ol class="breadcrumb mb-0" style="font-size:.8rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#A7F3D0;text-decoration:none">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:#D1FAE5">Cari LOA</li>
            </ol>
        </nav>
        <h1><i class="fas fa-search me-3" style="font-size:1.4rem"></i>Cari LOA</h1>
        <p>Masukkan kode LOA atau email penulis untuk menemukan dokumen yang sudah disetujui.</p>
    </div>
</div>

<div class="container py-5" style="padding-top:60px!important">
    <div class="row g-4 justify-content-center">
        <!-- Search Form -->
        <div class="col-lg-8">
            <div class="search-card">
                <div class="search-card-header">
                    <h4><i class="fas fa-file-search me-2"></i>Pencarian Dokumen LOA</h4>
                    <p class="mb-0 mt-1" style="font-size:.85rem;color:rgba(255,255,255,.75)">Masukkan kode LOA atau email penulis untuk mencari LOA yang sudah disetujui</p>
                </div>

                <div class="search-card-body">
                    <form action="{{ route('loa.find') }}" method="POST" id="searchForm">
                        @csrf

                        <label for="search" class="form-label">
                            Kode LOA atau Email Penulis <span style="color:#EF4444">*</span>
                        </label>

                        <div class="search-input-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   class="search-input @error('search') is-invalid @enderror"
                                   id="search"
                                   name="search"
                                   value="{{ old('search') }}"
                                   placeholder="Contoh: LOA20250801030918 atau email@kampus.ac.id"
                                   autocomplete="off"
                                   required>
                        </div>

                        @error('search')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror

                        <div class="search-hint">
                            <i class="fas fa-lightbulb" style="color:#F59E0B"></i>
                            <span>Cari dengan kode LOA seperti <code>LOA20250801030918</code> atau gunakan email penulis</span>
                        </div>
                    </form>
                </div>

                <div class="search-footer">
                    <a href="{{ route('home') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" form="searchForm" class="btn-search">
                        <i class="fas fa-search"></i> Cari LOA
                    </button>
                </div>
            </div>

            <!-- Help Card -->
            <div class="help-card">
                <div class="help-card-title">
                    <div style="width:28px;height:28px;background:#ECFDF5;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-question-circle" style="color:#059669;font-size:.85rem"></i>
                    </div>
                    Cara Pencarian
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="help-method">
                            <div class="help-method-title code">
                                <i class="fas fa-key"></i> Menggunakan Kode LOA
                            </div>
                            <ul>
                                <li>Format: LOA[Tanggal][Nomor Urut]</li>
                                <li>Contoh: <code style="font-size:.75rem;background:#E0F2FE;color:#0369A1;padding:2px 6px;border-radius:4px">LOA20250801030918</code></li>
                                <li>Kode diberikan setelah LOA disetujui</li>
                                <li>Cek email Anda untuk kode LOA</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="help-method">
                            <div class="help-method-title email">
                                <i class="fas fa-envelope"></i> Menggunakan Email Penulis
                            </div>
                            <ul>
                                <li>Gunakan email yang diisi saat request</li>
                                <li>Akan menampilkan semua LOA Anda</li>
                                <li>Hanya LOA yang sudah disetujui</li>
                                <li>Pastikan email dieja dengan benar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sidebar-card">
                <h6><i class="fas fa-info-circle"></i> Informasi Pencarian</h6>
                <div class="sidebar-tip">
                    <i class="fas fa-check-circle"></i>
                    <span>Hanya LOA dengan status <strong>Disetujui</strong> yang bisa ditemukan</span>
                </div>
                <div class="sidebar-tip">
                    <i class="fas fa-clock"></i>
                    <span>Proses review biasanya membutuhkan 1-3 hari kerja</span>
                </div>
                <div class="sidebar-tip">
                    <i class="fas fa-file-pdf"></i>
                    <span>LOA tersedia dalam format PDF bilingual (Indonesia & Inggris)</span>
                </div>
                <div class="sidebar-tip">
                    <i class="fas fa-qrcode"></i>
                    <span>Setiap LOA dilengkapi QR Code untuk verifikasi keaslian</span>
                </div>

                <div class="example-code">
                    <div class="label">Contoh Kode LOA</div>
                    LOA20250801030918
                </div>
            </div>

            <!-- Other options -->
            <div style="background:white;border:1px solid #E2E8F0;border-radius:16px;padding:20px;margin-top:16px">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748B;margin-bottom:14px">
                    Opsi Lainnya
                </div>
                <a href="{{ route('loa.verify') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500;margin-bottom:4px"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#FFFBEB;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#D97706;font-size:.85rem">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span>Verifikasi keaslian LOA</span>
                </a>
                <a href="{{ route('qr.scanner') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500;margin-bottom:4px"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#ECFEFF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0891B2;font-size:.85rem">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <span>Scan QR Code LOA</span>
                </a>
                <a href="{{ route('loa.create') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#EEF2FF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#4F46E5;font-size:.85rem">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <span>Buat permintaan LOA baru</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
