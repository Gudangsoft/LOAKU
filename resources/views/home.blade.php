@extends('layouts.app')

@section('title', 'Beranda - LOA SIPTENAN')

@push('styles')
<style>
    /* ── Hero ── */
    .hero-section {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 40%, #1E40AF 80%, #0E7490 100%);
        color: white;
        padding: 96px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 420px; height: 420px;
        background: radial-gradient(circle, rgba(99,102,241,.35) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -80px;
        width: 360px; height: 360px;
        background: radial-gradient(circle, rgba(6,182,212,.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,.2);
        color: #BAE6FD;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 30px;
        margin-bottom: 20px;
    }

    .hero-title {
        font-size: clamp(2rem, 4.5vw, 3.2rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 1.25rem;
        letter-spacing: -.5px;
    }

    .hero-title span {
        background: linear-gradient(90deg, #93C5FD, #67E8F9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-desc {
        font-size: 1.05rem;
        line-height: 1.75;
        color: #BAE6FD;
        max-width: 560px;
        margin-bottom: 2rem;
    }

    .hero-cta-group { display: flex; gap: 14px; flex-wrap: wrap; }

    .btn-hero-primary {
        background: white;
        color: #312E81;
        font-weight: 700;
        padding: 13px 28px;
        border-radius: 10px;
        border: none;
        font-size: 0.95rem;
        transition: all .25s;
        box-shadow: 0 4px 20px rgba(0,0,0,.2);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0,0,0,.3);
        color: #1E1B4B;
    }

    .btn-hero-outline {
        background: transparent;
        color: white;
        font-weight: 600;
        padding: 12px 28px;
        border-radius: 10px;
        border: 2px solid rgba(255,255,255,.4);
        font-size: 0.95rem;
        transition: all .25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
    }

    .btn-hero-outline:hover {
        background: rgba(255,255,255,.15);
        border-color: rgba(255,255,255,.7);
        color: white;
        transform: translateY(-2px);
    }

    .hero-visual {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .hero-card-float {
        background: rgba(255,255,255,.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 20px;
        padding: 32px;
        text-align: center;
        min-width: 260px;
    }

    .hero-icon-main {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, rgba(255,255,255,.25) 0%, rgba(255,255,255,.1) 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 16px;
        border: 1px solid rgba(255,255,255,.3);
    }

    .hero-card-stat {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 12px;
        padding: 10px 16px;
        margin-top: 10px;
        font-size: 0.875rem;
    }

    /* ── Stats ── */
    .stats-section { padding: 64px 0; }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 28px 24px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        transition: all .3s;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .stat-card:hover { transform: translateY(-6px); box-shadow: 0 12px 40px rgba(0,0,0,.12); }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }

    .stat-card.blue::before  { background: linear-gradient(90deg, #4F46E5, #06B6D4); }
    .stat-card.green::before { background: linear-gradient(90deg, #10B981, #059669); }
    .stat-card.amber::before { background: linear-gradient(90deg, #F59E0B, #EF4444); }
    .stat-card.cyan::before  { background: linear-gradient(90deg, #06B6D4, #3B82F6); }

    .stat-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 14px;
    }

    .stat-icon.blue  { background: #EEF2FF; color: #4F46E5; }
    .stat-icon.green { background: #ECFDF5; color: #10B981; }
    .stat-icon.amber { background: #FFFBEB; color: #F59E0B; }
    .stat-icon.cyan  { background: #ECFEFF; color: #06B6D4; }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1E293B;
        line-height: 1;
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748B;
        font-weight: 500;
    }

    /* ── Section header ── */
    .section-header { text-align: center; margin-bottom: 56px; }

    .section-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #EEF2FF;
        color: #4F46E5;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 14px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .section-title {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 12px;
    }

    .section-desc { color: #64748B; font-size: 1rem; max-width: 540px; margin: 0 auto; }

    /* ── Feature cards ── */
    .features-section { padding: 72px 0; background: #F8FAFC; }

    .feature-card {
        background: white;
        border-radius: 20px;
        padding: 36px 28px;
        border: 1px solid #E2E8F0;
        height: 100%;
        transition: all .3s;
        display: flex;
        flex-direction: column;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(0,0,0,.1);
        border-color: transparent;
    }

    .feature-icon {
        width: 60px; height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .feature-icon.indigo { background: linear-gradient(135deg, #EEF2FF, #C7D2FE); color: #4F46E5; }
    .feature-icon.green  { background: linear-gradient(135deg, #ECFDF5, #A7F3D0); color: #059669; }
    .feature-icon.cyan   { background: linear-gradient(135deg, #ECFEFF, #A5F3FC); color: #0891B2; }
    .feature-icon.amber  { background: linear-gradient(135deg, #FFFBEB, #FDE68A); color: #D97706; }

    .feature-card h5 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 10px;
    }

    .feature-card p {
        font-size: 0.9rem;
        color: #64748B;
        line-height: 1.65;
        flex: 1;
        margin-bottom: 20px;
    }

    .btn-feature {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 8px;
        transition: all .2s;
        align-self: flex-start;
    }

    .btn-feature.indigo { background: #EEF2FF; color: #4F46E5; }
    .btn-feature.indigo:hover { background: #4F46E5; color: white; }
    .btn-feature.green  { background: #ECFDF5; color: #059669; }
    .btn-feature.green:hover  { background: #059669; color: white; }
    .btn-feature.cyan   { background: #ECFEFF; color: #0891B2; }
    .btn-feature.cyan:hover   { background: #0891B2; color: white; }
    .btn-feature.amber  { background: #FFFBEB; color: #D97706; }
    .btn-feature.amber:hover  { background: #D97706; color: white; }

    /* ── How it works ── */
    .workflow-section { padding: 72px 0; }

    .workflow-step {
        position: relative;
        text-align: center;
    }

    .step-connector {
        position: absolute;
        top: 30px;
        left: calc(50% + 40px);
        width: calc(100% - 80px);
        height: 2px;
        background: linear-gradient(90deg, #C7D2FE, #A5F3FC);
        z-index: 0;
    }

    @media (max-width: 767px) {
        .step-connector { display: none; }
    }

    .step-circle {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        color: white;
        font-size: 1.3rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 20px rgba(79,70,229,.35);
    }

    .step-icon-wrap {
        width: 48px; height: 48px;
        background: #EEF2FF;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        color: #4F46E5;
        font-size: 1.1rem;
    }

    .workflow-step h5 {
        font-size: 1rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 8px;
    }

    .workflow-step p {
        font-size: 0.875rem;
        color: #64748B;
        line-height: 1.6;
    }

    /* ── Publishers ── */
    .publishers-section { padding: 72px 0; background: #F8FAFC; }

    .publisher-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
        height: 100%;
        transition: all .3s;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .publisher-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,.1);
        border-color: #C7D2FE;
    }

    .publisher-card .stretched-link::after {
        z-index: 1;
    }

    .publisher-footer {
        position: relative;
        z-index: 2;
        margin-top: auto;
    }

    .publisher-body {
        flex: 1;
        padding: 20px;
        position: relative;
    }

    .publisher-logo-wrap {
        background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 100%);
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #E2E8F0;
        padding: 16px;
    }

    .publisher-logo-placeholder {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 800;
    }

    .publisher-body h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .publisher-meta {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.8rem;
        color: #64748B;
        margin-bottom: 6px;
    }

    .publisher-meta i { margin-top: 2px; width: 14px; flex-shrink: 0; }

    .publisher-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
    }

    .journal-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #EEF2FF;
        color: #4F46E5;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
    }

    .publisher-links { display: flex; gap: 6px; }

    .publisher-link-btn {
        width: 28px; height: 28px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all .2s;
    }

    .publisher-link-btn.wa    { background: #DCFCE7; color: #16A34A; }
    .publisher-link-btn.wa:hover    { background: #16A34A; color: white; }
    .publisher-link-btn.web   { background: #DBEAFE; color: #2563EB; }
    .publisher-link-btn.web:hover   { background: #2563EB; color: white; }
    .publisher-link-btn.email { background: #FEF3C7; color: #D97706; }
    .publisher-link-btn.email:hover { background: #D97706; color: white; }

    /* ── CTA section ── */
    .cta-section {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 60%, #1E40AF 100%);
        padding: 72px 0;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%; left: -10%;
        width: 60%; height: 200%;
        background: radial-gradient(ellipse, rgba(99,102,241,.2) 0%, transparent 70%);
        pointer-events: none;
    }

    .cta-section h2 {
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 800;
        margin-bottom: 16px;
    }

    .cta-section p { color: #BAE6FD; font-size: 1.05rem; margin-bottom: 32px; }

    /* Animate counter */
    .stat-number[data-target] { transition: all .5s; }

    /* ── Supported By ── */
    .support-section {
        padding: 56px 0;
        background: white;
        border-top: 1px solid #F1F5F9;
    }

    .support-label {
        text-align: center;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94A3B8;
        margin-bottom: 32px;
    }

    .support-track {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 12px 24px;
    }

    .support-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        padding: 18px 24px;
        border-radius: 14px;
        border: 1px solid #F1F5F9;
        background: #FAFAFA;
        min-width: 130px;
        transition: all .25s;
    }

    .support-item:hover {
        border-color: #C7D2FE;
        background: #F5F3FF;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(79,70,229,.1);
    }

    .support-logo-wrap {
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .support-logo-wrap img {
        max-height: 52px;
        max-width: 110px;
        object-fit: contain;
        filter: grayscale(30%);
        transition: filter .25s;
    }

    .support-item:hover .support-logo-wrap img { filter: grayscale(0%); }

    .support-logo-placeholder {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 800;
        color: #4F46E5;
    }

    .support-name {
        font-size: .75rem;
        font-weight: 600;
        color: #64748B;
        text-align: center;
        line-height: 1.3;
        max-width: 110px;
    }

    .support-item:hover .support-name { color: #4F46E5; }
</style>
@endpush

@section('content')

<!-- ── HERO ── -->
<section class="hero-section">
    <div class="container position-relative" style="z-index:1">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <i class="fas fa-star" style="font-size:.7rem;color:#FCD34D"></i>
                    Platform LOA Jurnal Ilmiah Terpercaya
                </div>
                <h1 class="hero-title">
                    Kelola <span>Letter of Acceptance</span> Jurnal Ilmiah dengan Mudah
                </h1>
                <p class="hero-desc">
                    SIPTENAN menyederhanakan proses pengajuan, validasi, dan penerbitan LOA untuk artikel jurnal ilmiah Anda. Cepat, aman, dan terverifikasi QR Code.
                </p>
                <div class="hero-cta-group">
                    <a href="{{ route('loa.create') }}" class="btn-hero-primary">
                        <i class="fas fa-paper-plane"></i>
                        Request LOA Sekarang
                    </a>
                    <a href="{{ route('loa.search') }}" class="btn-hero-outline">
                        <i class="fas fa-search"></i>
                        Cari LOA Saya
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 mt-4" style="font-size:.8rem;color:#93C5FD">
                    <span><i class="fas fa-check-circle me-1" style="color:#34D399"></i> Gratis & Cepat</span>
                    <span><i class="fas fa-check-circle me-1" style="color:#34D399"></i> QR Terverifikasi</span>
                    <span><i class="fas fa-check-circle me-1" style="color:#34D399"></i> PDF Bilingual</span>
                </div>
            </div>
            <div class="col-lg-5 hero-visual">
                <div class="hero-card-float">
                    <div class="hero-icon-main">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div style="font-size:1rem;font-weight:700;margin-bottom:4px">LOA Terverifikasi</div>
                    <div style="font-size:.8rem;color:#BAE6FD;margin-bottom:16px">Letter of Acceptance Digital</div>
                    <div class="hero-card-stat">
                        <i class="fas fa-shield-check" style="color:#34D399;font-size:1rem"></i>
                        <span>Dokumen resmi dengan tanda tangan digital</span>
                    </div>
                    <div class="hero-card-stat">
                        <i class="fas fa-qrcode" style="color:#67E8F9;font-size:1rem"></i>
                        <span>QR Code unik untuk setiap LOA</span>
                    </div>
                    <div class="hero-card-stat">
                        <i class="fas fa-file-pdf" style="color:#FCA5A5;font-size:1rem"></i>
                        <span>Download PDF bilingual</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── STATISTICS ── -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card blue">
                    <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
                    <div class="stat-number" data-target="{{ $totalRequests }}">{{ $totalRequests }}</div>
                    <div class="stat-label">Total Permintaan</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-number" data-target="{{ $approvedRequests }}">{{ $approvedRequests }}</div>
                    <div class="stat-label">LOA Disetujui</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card amber">
                    <div class="stat-icon amber"><i class="fas fa-clock"></i></div>
                    <div class="stat-number" data-target="{{ $pendingRequests }}">{{ $pendingRequests }}</div>
                    <div class="stat-label">Menunggu Validasi</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card cyan">
                    <div class="stat-icon cyan"><i class="fas fa-book-open"></i></div>
                    <div class="stat-number" data-target="{{ $totalJournals }}">{{ $totalJournals }}</div>
                    <div class="stat-label">Jurnal Aktif</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURES ── -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fas fa-bolt"></i> Fitur Unggulan</div>
            <h2 class="section-title">Semua yang Anda Butuhkan</h2>
            <p class="section-desc">Dari pengajuan hingga verifikasi, semua tersedia dalam satu platform yang mudah digunakan.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="feature-card">
                    <div class="feature-icon indigo"><i class="fas fa-paper-plane"></i></div>
                    <h5>Request LOA</h5>
                    <p>Ajukan permintaan Letter of Acceptance secara online dengan form yang lengkap dan intuitif. Proses cepat dan mudah.</p>
                    <a href="{{ route('loa.create') }}" class="btn-feature indigo">
                        Mulai Request <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="feature-card">
                    <div class="feature-icon green"><i class="fas fa-search"></i></div>
                    <h5>Cari & Unduh LOA</h5>
                    <p>Temukan LOA yang sudah disetujui menggunakan kode LOA atau email penulis. Download PDF kapan saja.</p>
                    <a href="{{ route('loa.search') }}" class="btn-feature green">
                        Cari LOA <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="feature-card">
                    <div class="feature-icon cyan"><i class="fas fa-qrcode"></i></div>
                    <h5>Scan QR Code</h5>
                    <p>Verifikasi LOA secara instan dengan kamera perangkat Anda. Tidak perlu mengetik kode secara manual.</p>
                    <a href="{{ route('qr.scanner') }}" class="btn-feature cyan">
                        Scan Sekarang <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="feature-card">
                    <div class="feature-icon amber"><i class="fas fa-shield-alt"></i></div>
                    <h5>Verifikasi LOA</h5>
                    <p>Pastikan keaslian dokumen LOA dengan memasukkan kode unik. Sistem kami menjamin validitas setiap dokumen.</p>
                    <a href="{{ route('loa.verify') }}" class="btn-feature amber">
                        Verifikasi <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── HOW IT WORKS ── -->
<section class="workflow-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fas fa-route"></i> Alur Proses</div>
            <h2 class="section-title">Cara Kerja Sistem</h2>
            <p class="section-desc">Proses pengajuan LOA yang sederhana dan transparan dalam 4 langkah mudah.</p>
        </div>
        <div class="row g-4">
            <div class="col-6 col-md-3 workflow-step">
                <div class="step-connector d-none d-md-block"></div>
                <div class="step-circle">1</div>
                <div class="step-icon-wrap"><i class="fas fa-edit"></i></div>
                <h5>Isi Form</h5>
                <p>Lengkapi form permintaan LOA dengan data artikel, penulis, dan pilih jurnal yang sesuai.</p>
            </div>
            <div class="col-6 col-md-3 workflow-step">
                <div class="step-connector d-none d-md-block"></div>
                <div class="step-circle">2</div>
                <div class="step-icon-wrap"><i class="fas fa-tasks"></i></div>
                <h5>Review Admin</h5>
                <p>Tim admin mereview dan memvalidasi kelengkapan data permintaan LOA Anda dengan teliti.</p>
            </div>
            <div class="col-6 col-md-3 workflow-step">
                <div class="step-connector d-none d-md-block"></div>
                <div class="step-circle">3</div>
                <div class="step-icon-wrap"><i class="fas fa-certificate"></i></div>
                <h5>LOA Diterbitkan</h5>
                <p>LOA otomatis dibuat dengan kode unik dan QR Code setelah permintaan disetujui.</p>
            </div>
            <div class="col-6 col-md-3 workflow-step">
                <div class="step-circle">4</div>
                <div class="step-icon-wrap"><i class="fas fa-download"></i></div>
                <h5>Unduh PDF</h5>
                <p>Download LOA dalam format PDF bilingual (Indonesia & Inggris) kapan saja setelah disetujui.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── PUBLISHERS ── -->
<section class="publishers-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fas fa-building"></i> Mitra Publisher</div>
            <h2 class="section-title">Publisher Terdaftar</h2>
            <p class="section-desc">Bergabung bersama publisher jurnal ilmiah terpercaya yang telah bermitra dengan SIPTENAN.</p>
        </div>

        @if($publishers->count() > 0)
        <div class="row g-4">
            @foreach($publishers as $publisher)
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="publisher-card w-100">
                    <div class="publisher-logo-wrap">
                        @if($publisher->logo)
                            <img src="{{ asset('storage/' . $publisher->logo) }}"
                                 alt="{{ $publisher->name }}"
                                 style="max-height:70px;max-width:160px;object-fit:contain;">
                        @else
                            <div class="publisher-logo-placeholder">
                                {{ strtoupper(substr($publisher->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="publisher-body">
                        <h5>
                            <a href="{{ route('publishers.detail', $publisher->id) }}"
                               class="stretched-link text-decoration-none"
                               style="color:#1E293B">{{ $publisher->name }}</a>
                        </h5>
                        @if($publisher->address)
                        <div class="publisher-meta">
                            <i class="fas fa-map-marker-alt" style="color:#4F46E5"></i>
                            <span>{{ Str::limit($publisher->address, 55) }}</span>
                        </div>
                        @endif
                        @if($publisher->email)
                        <div class="publisher-meta">
                            <i class="fas fa-envelope" style="color:#F59E0B"></i>
                            <span>{{ $publisher->email }}</span>
                        </div>
                        @endif
                        @if($publisher->phone)
                        <div class="publisher-meta">
                            <i class="fas fa-phone" style="color:#10B981"></i>
                            <span>{{ $publisher->phone }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="publisher-footer">
                        <div class="journal-badge">
                            <i class="fas fa-book"></i>
                            {{ $publisher->journals->count() }} Jurnal
                        </div>
                        <div class="publisher-links">
                            @if($publisher->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $publisher->whatsapp) }}"
                               target="_blank" class="publisher-link-btn wa" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            @endif
                            @if($publisher->website)
                            <a href="{{ $publisher->website }}" target="_blank" class="publisher-link-btn web" title="Website">
                                <i class="fas fa-globe"></i>
                            </a>
                            @endif
                            @if($publisher->email)
                            <a href="mailto:{{ $publisher->email }}" class="publisher-link-btn email" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($totalPublishers > 6)
        <div class="text-center mt-5">
            <a href="{{ route('publishers.index') }}" class="btn btn-outline-primary px-5 py-3">
                <i class="fas fa-building me-2"></i>Lihat Semua Publisher ({{ $totalPublishers }})
            </a>
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <div style="width:80px;height:80px;background:#EEF2FF;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;color:#4F46E5">
                <i class="fas fa-building"></i>
            </div>
            <h5 style="color:#1E293B;font-weight:700">Belum Ada Publisher</h5>
            <p style="color:#64748B">Publisher belum terdaftar saat ini.</p>
            <a href="{{ route('publisher.register.form') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Daftar sebagai Publisher
            </a>
        </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('publishers.index') }}" style="font-size:.875rem;color:#4F46E5;text-decoration:none;font-weight:600">
                <i class="fas fa-building me-1"></i>Lihat semua publisher
                <i class="fas fa-arrow-right ms-1" style="font-size:.75rem"></i>
            </a>
        </div>
    </div>
</section>

<!-- ── SUPPORTED BY ── -->
@if($supports->count() > 0)
<section class="support-section">
    <div class="container">
        <div class="support-label">
            <i class="fas fa-handshake me-2"></i>Didukung Oleh
        </div>
        <div class="support-track">
            @foreach($supports as $support)
            @if($support->website)
            <a href="{{ $support->website }}" target="_blank" rel="noopener" class="support-item" title="{{ $support->name }}">
            @else
            <div class="support-item">
            @endif
                <div class="support-logo-wrap">
                    @if($support->logo)
                        <img src="{{ $support->logo_url }}" alt="{{ $support->name }}">
                    @else
                        <div class="support-logo-placeholder">
                            {{ strtoupper(substr($support->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="support-name">{{ $support->name }}</div>
            @if($support->website)
            </a>
            @else
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── CTA SECTION ── -->
<section class="cta-section">
    <div class="container position-relative" style="z-index:1">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h2>Siap Mengajukan LOA Anda?</h2>
                <p>Proses cepat, mudah, dan terpercaya. Ribuan penulis telah menggunakan SIPTENAN untuk jurnal ilmiah mereka.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('loa.create') }}" class="btn-hero-primary">
                        <i class="fas fa-paper-plane"></i> Request LOA Gratis
                    </a>
                    <a href="{{ route('loa.search') }}" class="btn-hero-outline">
                        <i class="fas fa-search"></i> Cari LOA Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Animate stat counters
    function animateCounter(el) {
        const target = parseInt(el.dataset.target || el.textContent);
        const duration = 1500;
        const step = Math.ceil(target / (duration / 16));
        let current = 0;
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 16);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-target]').forEach(el => observer.observe(el));
</script>
@endpush
