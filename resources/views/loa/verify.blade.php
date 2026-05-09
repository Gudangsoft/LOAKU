@extends('layouts.app')

@section('title', 'Verifikasi LOA - LOA SIPTENAN')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #78350F 0%, #92400E 50%, #B45309 100%);
        color: white;
        padding: 48px 0 40px;
        margin-bottom: -40px;
    }

    .page-header h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 6px; }
    .page-header p  { color: #FDE68A; font-size: 0.9rem; margin: 0; }

    .verify-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
        overflow: hidden;
    }

    .verify-card-header {
        background: linear-gradient(135deg, #D97706 0%, #EF4444 100%);
        padding: 24px 32px;
        color: white;
    }

    .verify-card-header h4 { font-weight: 700; font-size: 1.1rem; margin: 0; }

    .verify-card-body { padding: 32px; }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .verify-input-wrap { position: relative; }

    .verify-input-wrap .verify-input {
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 18px 14px 50px;
        font-size: 1rem;
        color: #1E293B;
        width: 100%;
        transition: border-color .2s, box-shadow .2s;
        background: #FAFAFA;
        font-family: monospace;
        letter-spacing: .5px;
    }

    .verify-input-wrap .verify-input:focus {
        border-color: #D97706;
        box-shadow: 0 0 0 3px rgba(217,119,6,.12);
        background: white;
        outline: none;
    }

    .verify-input-wrap .verify-input.is-invalid {
        border-color: #EF4444;
        background: #FFF5F5;
    }

    .verify-input-wrap .verify-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #D97706;
        font-size: 1rem;
        pointer-events: none;
    }

    .invalid-feedback { font-size: 0.8rem; color: #EF4444; margin-top: 5px; }

    .verify-hint {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        color: #64748B;
        margin-top: 8px;
    }

    .verify-hint code {
        background: #FFFBEB;
        color: #D97706;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.78rem;
    }

    .verify-footer {
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

    .btn-verify {
        background: linear-gradient(135deg, #D97706 0%, #EF4444 100%);
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

    .btn-verify:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(217,119,6,.35); }

    .info-card {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 16px;
        padding: 24px;
        margin-top: 20px;
    }

    .info-card-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #92400E;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-col-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #78350F;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.85rem;
        color: #92400E;
        margin-bottom: 6px;
        line-height: 1.5;
    }

    .info-item i { color: #D97706; margin-top: 2px; flex-shrink: 0; }

    .tip-box {
        background: #FEF3C7;
        border: 1px solid #FCD34D;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 16px;
        font-size: 0.83rem;
        color: #78350F;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .tip-box i { color: #F59E0B; margin-top: 2px; flex-shrink: 0; }

    /* Sidebar */
    .sidebar-col {
        position: sticky;
        top: 80px;
        align-self: flex-start;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
    .sidebar-card {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 16px;
        padding: 24px;
    }

    .sidebar-card h6 {
        font-size: 0.875rem;
        font-weight: 700;
        color: #92400E;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .status-chip.valid { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
    .status-chip.invalid { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    .status-chip.pending { background: #FFFBEB; color: #78350F; border: 1px solid #FDE68A; }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:12px">
            <ol class="breadcrumb mb-0" style="font-size:.8rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#FDE68A;text-decoration:none">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:#FEF3C7">Verifikasi LOA</li>
            </ol>
        </nav>
        <h1><i class="fas fa-shield-alt me-3" style="font-size:1.4rem"></i>Verifikasi LOA</h1>
        <p>Masukkan kode LOA untuk memverifikasi keaslian dan validitas dokumen.</p>
    </div>
</div>

<div class="container py-5" style="padding-top:60px!important">
    <div class="row g-4 justify-content-center align-items-start">
        <!-- Verify Form -->
        <div class="col-lg-8">
            <div class="verify-card">
                <div class="verify-card-header">
                    <h4><i class="fas fa-shield-check me-2"></i>Verifikasi Keaslian Dokumen LOA</h4>
                    <p class="mb-0 mt-1" style="font-size:.85rem;color:rgba(255,255,255,.75)">Masukkan kode LOA untuk memastikan keaslian dan validitas dokumen</p>
                </div>

                <div class="verify-card-body">
                    <form action="{{ route('loa.check') }}" method="POST" id="verifyForm">
                        @csrf

                        <label for="loa_code" class="form-label">
                            Kode LOA <span style="color:#EF4444">*</span>
                        </label>

                        <div class="verify-input-wrap">
                            <i class="fas fa-key verify-icon"></i>
                            <input type="text"
                                   class="verify-input @error('loa_code') is-invalid @enderror"
                                   id="loa_code"
                                   name="loa_code"
                                   value="{{ old('loa_code') }}"
                                   placeholder="Contoh: LOA20250801001"
                                   autocomplete="off"
                                   required>
                        </div>

                        @error('loa_code')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror

                        <div class="verify-hint">
                            <i class="fas fa-info-circle" style="color:#D97706"></i>
                            <span>Format kode LOA: <code>LOA[Tanggal][Nomor]</code>, contoh: <code>LOA20250801001</code></span>
                        </div>
                    </form>
                </div>

                <div class="verify-footer">
                    <a href="{{ route('home') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" form="verifyForm" class="btn-verify">
                        <i class="fas fa-shield-alt"></i> Verifikasi Sekarang
                    </button>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card">
                <div class="info-card-title">
                    <div style="width:28px;height:28px;background:#FEF3C7;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-info-circle" style="color:#D97706;font-size:.85rem"></i>
                    </div>
                    Tentang Verifikasi LOA
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-col-title"><i class="fas fa-check-circle me-1" style="color:#D97706"></i>Fungsi Verifikasi</div>
                        <div class="info-item"><i class="fas fa-check-circle"></i><span>Memastikan keaslian dokumen LOA</span></div>
                        <div class="info-item"><i class="fas fa-check-circle"></i><span>Mencegah pemalsuan dokumen</span></div>
                        <div class="info-item"><i class="fas fa-check-circle"></i><span>Validasi data artikel dan penulis</span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-col-title"><i class="fas fa-list me-1" style="color:#D97706"></i>Hasil Verifikasi</div>
                        <div class="info-item"><i class="fas fa-check-circle"></i><span>Status validitas LOA</span></div>
                        <div class="info-item"><i class="fas fa-check-circle"></i><span>Detail informasi artikel lengkap</span></div>
                        <div class="info-item"><i class="fas fa-check-circle"></i><span>Tanggal persetujuan resmi</span></div>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="fas fa-lightbulb"></i>
                    <span><strong>Tips:</strong> Kode LOA yang valid akan menampilkan informasi lengkap artikel beserta status "Terverifikasi". Jika tidak ditemukan, pastikan kode yang Anda masukkan sudah benar.</span>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 sidebar-col">
            <div class="sidebar-card">
                <h6><i class="fas fa-shield-alt"></i> Status Verifikasi</h6>
                <p style="font-size:.8rem;color:#92400E;margin-bottom:16px">Hasil verifikasi akan menampilkan salah satu status berikut:</p>

                <div class="status-chip valid">
                    <i class="fas fa-check-circle"></i> Terverifikasi & Valid
                </div>
                <p style="font-size:.78rem;color:#64748B;margin-bottom:12px">LOA asli dan telah disetujui oleh admin.</p>

                <div class="status-chip invalid">
                    <i class="fas fa-times-circle"></i> Tidak Ditemukan
                </div>
                <p style="font-size:.78rem;color:#64748B;margin-bottom:12px">Kode tidak valid atau LOA belum terdaftar.</p>

                <div class="status-chip pending">
                    <i class="fas fa-clock"></i> Menunggu Persetujuan
                </div>
                <p style="font-size:.78rem;color:#64748B;margin-bottom:0">LOA masih dalam proses review admin.</p>
            </div>

            <!-- Quick links -->
            <div style="background:white;border:1px solid #E2E8F0;border-radius:16px;padding:20px;margin-top:16px">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748B;margin-bottom:14px">
                    Akses Cepat
                </div>
                <a href="{{ route('qr.scanner') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500;margin-bottom:4px"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#ECFEFF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0891B2;font-size:.85rem">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <span>Scan QR Code (lebih mudah)</span>
                </a>
                <a href="{{ route('loa.search') }}" style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;font-size:.875rem;font-weight:500;margin-bottom:4px"
                   onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;background:#ECFDF5;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#059669;font-size:.85rem">
                        <i class="fas fa-search"></i>
                    </div>
                    <span>Cari LOA dengan email</span>
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
