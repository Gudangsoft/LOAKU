@extends('layouts.app')

@section('title', 'Scan QR Code LOA - LOA SIPTENAN')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #0C4A6E 0%, #075985 50%, #0369A1 100%);
        color: white;
        padding: 48px 0 44px;
        margin-bottom: -44px;
    }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 6px; }
    .page-header p  { color: #BAE6FD; font-size: 0.9rem; margin: 0; }

    .scanner-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .scanner-header {
        background: linear-gradient(135deg, #0369A1 0%, #06B6D4 100%);
        padding: 20px 28px;
        color: white;
    }
    .scanner-header h4 { font-weight: 700; font-size: 1.05rem; margin: 0; }

    /* ── Scanner viewport ── */
    #scanner-viewport {
        position: relative;
        background: #0F172A;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    #html5-qrcode-scanner {
        width: 100%;
    }

    /* Override html5-qrcode default styles */
    #html5-qrcode-scanner > img { display: none !important; }

    #qr-shaded-region {
        border: none !important;
    }

    .scan-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
    }

    .scan-frame {
        width: 220px;
        height: 220px;
        position: relative;
    }

    .scan-frame::before,
    .scan-frame::after {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        border-color: #06B6D4;
        border-style: solid;
    }

    .scan-frame::before {
        top: 0; left: 0;
        border-width: 4px 0 0 4px;
        border-radius: 4px 0 0 0;
    }

    .scan-frame::after {
        bottom: 0; right: 0;
        border-width: 0 4px 4px 0;
        border-radius: 0 0 4px 0;
    }

    .scan-frame-tl, .scan-frame-tr, .scan-frame-bl, .scan-frame-br {
        position: absolute;
        width: 40px; height: 40px;
        border-color: #06B6D4;
        border-style: solid;
    }
    .scan-frame-tr { top: 0; right: 0; border-width: 4px 4px 0 0; border-radius: 0 4px 0 0; }
    .scan-frame-bl { bottom: 0; left: 0; border-width: 0 0 4px 4px; border-radius: 0 0 0 4px; }

    .scan-line {
        position: absolute;
        left: 10%;
        width: 80%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #06B6D4, transparent);
        animation: scanline 2s linear infinite;
        top: 0;
    }

    @keyframes scanline {
        0%   { top: 10%; opacity: 1; }
        90%  { opacity: 1; }
        100% { top: 90%; opacity: 0; }
    }

    /* ── Controls ── */
    .scanner-controls {
        padding: 20px 24px;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }

    .btn-scan-start {
        background: linear-gradient(135deg, #0369A1, #06B6D4);
        color: white; border: none;
        padding: 10px 24px; border-radius: 10px;
        font-weight: 700; font-size: .9rem;
        display: inline-flex; align-items: center; gap: 8px;
        cursor: pointer; transition: all .2s;
    }
    .btn-scan-start:hover { opacity: .9; transform: translateY(-1px); }

    .btn-scan-stop {
        background: white;
        color: #EF4444; border: 2px solid #EF4444;
        padding: 10px 24px; border-radius: 10px;
        font-weight: 700; font-size: .9rem;
        display: inline-flex; align-items: center; gap: 8px;
        cursor: pointer; transition: all .2s;
    }
    .btn-scan-stop:hover { background: #FEF2F2; }

    .btn-switch-cam {
        background: white;
        color: #4F46E5; border: 2px solid #C7D2FE;
        padding: 10px 18px; border-radius: 10px;
        font-weight: 600; font-size: .875rem;
        display: inline-flex; align-items: center; gap: 7px;
        cursor: pointer; transition: all .2s;
    }
    .btn-switch-cam:hover { background: #EEF2FF; border-color: #4F46E5; }

    /* ── Status bar ── */
    .status-bar {
        padding: 10px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: .85rem;
        font-weight: 500;
        border-top: 1px solid #E2E8F0;
        min-height: 44px;
    }
    .status-bar.idle    { background: #F8FAFC; color: #64748B; }
    .status-bar.active  { background: #ECFEFF; color: #0369A1; }
    .status-bar.success { background: #ECFDF5; color: #065F46; }
    .status-bar.error   { background: #FEF2F2; color: #991B1B; }

    .status-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .status-dot.idle    { background: #94A3B8; }
    .status-dot.active  { background: #0EA5E9; animation: pulse-dot 1s infinite; }
    .status-dot.success { background: #10B981; }
    .status-dot.error   { background: #EF4444; }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .6; transform: scale(1.4); }
    }

    /* ── Result box ── */
    .result-box {
        margin: 20px 24px;
        border-radius: 14px;
        padding: 20px;
        display: none;
    }
    .result-box.success {
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
    }
    .result-box.error {
        background: #FEF2F2;
        border: 1px solid #FECACA;
    }

    .result-box h5 { font-size: .95rem; font-weight: 700; margin-bottom: 6px; }
    .result-box .code-display {
        font-family: monospace;
        font-size: 1rem;
        background: rgba(0,0,0,.06);
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-block;
        margin-bottom: 14px;
        letter-spacing: .5px;
    }

    .btn-verify-result {
        background: linear-gradient(135deg, #059669, #0891B2);
        color: white; border: none;
        padding: 9px 22px; border-radius: 9px;
        font-weight: 700; font-size: .875rem;
        display: inline-flex; align-items: center; gap: 7px;
        cursor: pointer; transition: all .2s; text-decoration: none;
        margin-right: 8px;
    }
    .btn-verify-result:hover { opacity: .9; transform: translateY(-1px); color: white; }

    .btn-scan-again {
        background: white;
        color: #64748B; border: 1.5px solid #E2E8F0;
        padding: 9px 18px; border-radius: 9px;
        font-weight: 600; font-size: .875rem;
        display: inline-flex; align-items: center; gap: 7px;
        cursor: pointer; transition: all .2s;
    }
    .btn-scan-again:hover { background: #F8FAFC; color: #1E293B; }

    /* ── Divider ── */
    .or-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 24px;
        color: #94A3B8; font-size: .8rem; font-weight: 600;
    }
    .or-divider::before, .or-divider::after {
        content: ''; flex: 1; height: 1px; background: #E2E8F0;
    }

    /* ── Manual input ── */
    .manual-section { padding: 0 24px 24px; }
    .manual-section label { font-size: .875rem; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
    .manual-input-wrap { position: relative; }
    .manual-input-wrap input {
        border: 2px solid #E2E8F0;
        border-radius: 10px;
        padding: 11px 16px 11px 42px;
        font-size: .9rem;
        width: 100%;
        font-family: monospace;
        letter-spacing: .3px;
        transition: border-color .2s, box-shadow .2s;
        background: #FAFAFA;
    }
    .manual-input-wrap input:focus {
        outline: none;
        border-color: #0369A1;
        box-shadow: 0 0 0 3px rgba(3,105,161,.12);
        background: white;
    }
    .manual-input-wrap .mi { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; }
    .btn-manual-verify {
        background: linear-gradient(135deg, #0369A1, #06B6D4);
        color: white; border: none;
        padding: 11px 22px; border-radius: 10px;
        font-weight: 700; font-size: .875rem;
        display: inline-flex; align-items: center; gap: 7px;
        cursor: pointer; transition: all .2s; white-space: nowrap;
    }
    .btn-manual-verify:hover { opacity: .9; transform: translateY(-1px); }

    /* ── Sidebar ── */
    .steps-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 22px;
        position: sticky;
        top: 80px;
    }
    .steps-card-title {
        font-size: .8rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: #0369A1; margin-bottom: 16px;
        display: flex; align-items: center; gap: 7px;
    }
    .step-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
    .step-num {
        width: 26px; height: 26px; border-radius: 50%;
        background: linear-gradient(135deg, #0369A1, #06B6D4);
        color: white; font-size: .75rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 1px;
    }
    .step-text { font-size: .825rem; color: #475569; line-height: 1.5; }
    .step-text strong { color: #1E293B; }

    .tips-card {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 16px;
        padding: 20px;
        margin-top: 14px;
    }
    .tips-card-title { font-size: .8rem; font-weight: 700; color: #92400E; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
    .tip-item { display: flex; align-items: flex-start; gap: 8px; font-size: .8rem; color: #78350F; margin-bottom: 8px; line-height: 1.5; }
    .tip-item i { color: #D97706; margin-top: 2px; flex-shrink: 0; }

    /* idle placeholder */
    #camera-idle {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        min-height: 280px; color: #94A3B8; padding: 40px;
    }
    #camera-idle i { font-size: 3.5rem; margin-bottom: 16px; opacity: .4; }
    #camera-idle p { font-size: .9rem; text-align: center; line-height: 1.6; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:12px">
            <ol class="breadcrumb mb-0" style="font-size:.8rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#BAE6FD;text-decoration:none">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:#E0F2FE">Scan QR Code</li>
            </ol>
        </nav>
        <h1><i class="fas fa-qrcode me-3" style="font-size:1.4rem"></i>Scan QR Code LOA</h1>
        <p>Arahkan kamera ke QR Code pada dokumen LOA untuk verifikasi otomatis.</p>
    </div>
</div>

<div class="container py-5" style="padding-top:60px!important">
    <div class="row g-4 justify-content-center">

        <!-- Scanner -->
        <div class="col-lg-8">
            <div class="scanner-card">
                <div class="scanner-header">
                    <h4><i class="fas fa-camera me-2"></i>Kamera Scanner</h4>
                    <p class="mb-0 mt-1" style="font-size:.82rem;color:rgba(255,255,255,.75)">Gunakan kamera untuk memindai QR Code LOA secara otomatis</p>
                </div>

                <!-- Viewport -->
                <div id="scanner-viewport">
                    <!-- Idle state -->
                    <div id="camera-idle">
                        <i class="fas fa-qrcode"></i>
                        <p>Tekan <strong style="color:#06B6D4">Mulai Scan</strong> untuk mengaktifkan kamera<br>
                           dan arahkan ke QR Code dokumen LOA.</p>
                    </div>

                    <!-- html5-qrcode container -->
                    <div id="html5-qrcode-scanner" style="display:none;width:100%"></div>

                    <!-- Scan overlay (shown when active) -->
                    <div class="scan-overlay" id="scan-overlay" style="display:none">
                        <div class="scan-frame">
                            <div class="scan-frame-tr"></div>
                            <div class="scan-frame-bl"></div>
                            <div class="scan-line"></div>
                        </div>
                    </div>
                </div>

                <!-- Status bar -->
                <div class="status-bar idle" id="status-bar">
                    <div class="status-dot idle" id="status-dot"></div>
                    <span id="status-text">Siap — tekan tombol di bawah untuk mulai scan</span>
                </div>

                <!-- Result box -->
                <div class="result-box" id="result-box">
                    <h5 id="result-title"></h5>
                    <div class="code-display" id="result-code"></div><br>
                    <div id="result-actions"></div>
                </div>

                <!-- Controls -->
                <div class="scanner-controls">
                    <button class="btn-scan-start" id="btn-start">
                        <i class="fas fa-play"></i> Mulai Scan
                    </button>
                    <button class="btn-scan-stop" id="btn-stop" style="display:none">
                        <i class="fas fa-stop"></i> Hentikan
                    </button>
                    <button class="btn-switch-cam" id="btn-switch" style="display:none">
                        <i class="fas fa-sync-alt"></i> Ganti Kamera
                    </button>
                </div>

                <!-- Divider -->
                <div class="or-divider">Atau masukkan kode secara manual</div>

                <!-- Manual input -->
                <div class="manual-section">
                    <label for="manual-code">Kode LOA</label>
                    <div class="d-flex gap-2">
                        <form action="{{ route('loa.check') }}" method="POST" class="d-flex gap-2 w-100">
                            @csrf
                            <div class="manual-input-wrap flex-grow-1">
                                <i class="fas fa-key mi"></i>
                                <input type="text" name="loa_code" id="manual-code"
                                       placeholder="Contoh: LOA20250801001"
                                       autocomplete="off" required>
                            </div>
                            <button type="submit" class="btn-manual-verify">
                                <i class="fas fa-shield-alt"></i> Verifikasi
                            </button>
                        </form>
                    </div>
                    <div style="font-size:.75rem;color:#94A3B8;margin-top:6px">
                        <i class="fas fa-info-circle me-1"></i>
                        Format kode: <code style="background:#F1F5F9;padding:2px 6px;border-radius:4px;color:#0369A1">LOA20250801001</code>
                    </div>
                </div>
            </div>

            <!-- Quick links -->
            <div class="d-flex gap-3 mt-4 flex-wrap">
                <a href="{{ route('loa.verify') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:white;border:1px solid #E2E8F0;border-radius:10px;text-decoration:none;color:#374151;font-size:.875rem;font-weight:600;transition:all .2s"
                   onmouseover="this.style.borderColor='#C7D2FE';this.style.color='#4F46E5'" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#374151'">
                    <i class="fas fa-keyboard" style="color:#4F46E5"></i> Verifikasi Manual
                </a>
                <a href="{{ route('loa.search') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:white;border:1px solid #E2E8F0;border-radius:10px;text-decoration:none;color:#374151;font-size:.875rem;font-weight:600;transition:all .2s"
                   onmouseover="this.style.borderColor='#A7F3D0';this.style.color='#059669'" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#374151'">
                    <i class="fas fa-search" style="color:#059669"></i> Cari LOA
                </a>
                <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:white;border:1px solid #E2E8F0;border-radius:10px;text-decoration:none;color:#374151;font-size:.875rem;font-weight:600;transition:all .2s"
                   onmouseover="this.style.borderColor='#E2E8F0';this.style.color='#64748B'" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#374151'">
                    <i class="fas fa-home" style="color:#64748B"></i> Beranda
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="steps-card">
                <div class="steps-card-title">
                    <i class="fas fa-list-ol"></i> Cara Menggunakan
                </div>
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text">Tekan <strong>Mulai Scan</strong> untuk mengaktifkan kamera perangkat Anda</div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text"><strong>Izinkan akses kamera</strong> saat browser meminta konfirmasi</div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text">Arahkan kamera ke <strong>QR Code</strong> yang ada di dokumen LOA</div>
                </div>
                <div class="step-item">
                    <div class="step-num">4</div>
                    <div class="step-text">Sistem <strong>otomatis mendeteksi</strong> dan memverifikasi LOA</div>
                </div>
                <div class="step-item" style="margin-bottom:0">
                    <div class="step-num">5</div>
                    <div class="step-text">Tekan <strong>Verifikasi LOA</strong> untuk melihat detail dokumen</div>
                </div>
            </div>

            <div class="tips-card">
                <div class="tips-card-title">
                    <i class="fas fa-lightbulb"></i> Tips Scan Terbaik
                </div>
                <div class="tip-item">
                    <i class="fas fa-sun"></i>
                    <span>Pastikan pencahayaan cukup terang, hindari bayangan di atas QR Code</span>
                </div>
                <div class="tip-item">
                    <i class="fas fa-ruler-combined"></i>
                    <span>Jaga jarak 10–20 cm antara kamera dan QR Code</span>
                </div>
                <div class="tip-item">
                    <i class="fas fa-hand-paper"></i>
                    <span>Tahan kamera dengan stabil agar tidak blur saat scanning</span>
                </div>
                <div class="tip-item" style="margin-bottom:0">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Gunakan kamera belakang (rear) untuk hasil terbaik</span>
                </div>
            </div>

            <!-- HTTPS warning -->
            <div id="https-warning" style="display:none;background:#FFFBEB;border:1px solid #FDE68A;border-radius:12px;padding:16px;margin-top:14px;font-size:.8rem;color:#92400E">
                <i class="fas fa-exclamation-triangle me-2" style="color:#D97706"></i>
                <strong>Perhatian:</strong> Akses kamera memerlukan koneksi HTTPS. Jika kamera tidak berfungsi, gunakan input manual di bawah.
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Show HTTPS warning if not secure
    if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
        document.getElementById('https-warning').style.display = 'block';
    }

    const btnStart   = document.getElementById('btn-start');
    const btnStop    = document.getElementById('btn-stop');
    const btnSwitch  = document.getElementById('btn-switch');
    const statusBar  = document.getElementById('status-bar');
    const statusDot  = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');
    const resultBox  = document.getElementById('result-box');
    const resultTitle= document.getElementById('result-title');
    const resultCode = document.getElementById('result-code');
    const resultActs = document.getElementById('result-actions');
    const cameraIdle = document.getElementById('camera-idle');
    const scannerDiv = document.getElementById('html5-qrcode-scanner');
    const scanOverlay= document.getElementById('scan-overlay');

    let html5QrCode  = null;
    let cameras      = [];
    let currentCamIdx= 0;
    let scanning     = false;

    function setStatus(type, text) {
        statusBar.className  = 'status-bar ' + type;
        statusDot.className  = 'status-dot ' + type;
        statusText.textContent = text;
    }

    function extractLoaCode(raw) {
        let code = raw.trim();
        // Strip URL prefix patterns
        const patterns = ['/verify-qr/', '/verify-loa/', '/qr/'];
        for (const p of patterns) {
            if (code.includes(p)) {
                code = code.split(p).pop();
                break;
            }
        }
        // Remove query string, hash, trailing slash
        code = code.split('?')[0].split('#')[0].replace(/\/$/, '').trim();
        return code;
    }

    function isValidLoaCode(code) {
        return /^LOA\d+$/i.test(code) ||          // LOAxxxx format
               /^\d{8,}\d{3}$/.test(code) ||       // numeric formats
               /^\d+\//.test(code);                 // registration number
    }

    function showResult(success, code, rawContent) {
        resultBox.style.display = 'block';
        resultBox.className = 'result-box ' + (success ? 'success' : 'error');

        if (success) {
            resultTitle.innerHTML = '<i class="fas fa-check-circle me-2" style="color:#10B981"></i>QR Code Terdeteksi!';
            resultTitle.style.color = '#065F46';
            resultCode.textContent = code;

            const verifyUrl = code.match(/^LOA/i)
                ? '/verify-qr/' + encodeURIComponent(code)
                : '/verify-loa/' + encodeURIComponent(code);

            resultActs.innerHTML = `
                <a href="${verifyUrl}" class="btn-verify-result">
                    <i class="fas fa-shield-alt"></i> Verifikasi LOA Ini
                </a>
                <button class="btn-scan-again" id="btn-again">
                    <i class="fas fa-redo"></i> Scan Lagi
                </button>`;
        } else {
            resultTitle.innerHTML = '<i class="fas fa-exclamation-circle me-2" style="color:#EF4444"></i>Format Tidak Dikenal';
            resultTitle.style.color = '#991B1B';
            resultCode.textContent = rawContent.length > 60 ? rawContent.substring(0, 60) + '…' : rawContent;

            resultActs.innerHTML = `
                <button class="btn-scan-again" id="btn-again">
                    <i class="fas fa-redo"></i> Coba Scan Lagi
                </button>`;
        }

        document.getElementById('btn-again')?.addEventListener('click', function () {
            resultBox.style.display = 'none';
            startScanning();
        });
    }

    function onScanSuccess(decodedText) {
        stopScanning();
        const code = extractLoaCode(decodedText);
        const valid = isValidLoaCode(code);

        setStatus(valid ? 'success' : 'error',
            valid ? 'QR Code berhasil terdeteksi: ' + code : 'QR Code tidak dikenali sebagai kode LOA valid');

        showResult(valid, code, decodedText);
    }

    function startScanning() {
        if (scanning) return;

        resultBox.style.display = 'none';
        cameraIdle.style.display = 'none';
        scannerDiv.style.display = 'block';
        scanOverlay.style.display = 'flex';

        btnStart.style.display = 'none';
        btnStop.style.display  = 'inline-flex';

        setStatus('active', 'Mengaktifkan kamera…');

        html5QrCode = new Html5Qrcode('html5-qrcode-scanner');

        const camId = cameras.length > 0 ? cameras[currentCamIdx].id : { facingMode: 'environment' };

        html5QrCode.start(
            camId,
            { fps: 10, qrbox: { width: 220, height: 220 }, aspectRatio: 1.2 },
            onScanSuccess
        ).then(() => {
            scanning = true;
            setStatus('active', 'Kamera aktif — arahkan ke QR Code LOA');
            if (cameras.length > 1) btnSwitch.style.display = 'inline-flex';
        }).catch(err => {
            console.error(err);
            cameraIdle.style.display = 'flex';
            scannerDiv.style.display = 'none';
            scanOverlay.style.display = 'none';
            btnStart.style.display = 'inline-flex';
            btnStop.style.display  = 'none';

            const msg = err.toString().toLowerCase().includes('permission')
                ? 'Akses kamera ditolak. Izinkan akses kamera di pengaturan browser Anda.'
                : 'Gagal mengakses kamera: ' + err;
            setStatus('error', msg);
        });
    }

    function stopScanning() {
        if (!html5QrCode || !scanning) return;
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            html5QrCode = null;
            scanning = false;
        }).catch(() => {});

        scanOverlay.style.display = 'none';
        btnStop.style.display   = 'none';
        btnSwitch.style.display = 'none';
        btnStart.style.display  = 'inline-flex';

        if (!resultBox.style.display || resultBox.style.display === 'none') {
            cameraIdle.style.display = 'flex';
            scannerDiv.style.display = 'none';
            setStatus('idle', 'Scan dihentikan');
        }
    }

    btnStart.addEventListener('click', startScanning);
    btnStop.addEventListener('click', stopScanning);

    btnSwitch.addEventListener('click', function () {
        if (cameras.length < 2 || !html5QrCode) return;
        currentCamIdx = (currentCamIdx + 1) % cameras.length;
        html5QrCode.stop().then(() => {
            scanning = false;
            startScanning();
        }).catch(() => {});
    });

    // Enumerate cameras
    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length > 0) {
            cameras = devices;
            // Prefer rear camera
            const rearIdx = devices.findIndex(d =>
                d.label.toLowerCase().includes('back') ||
                d.label.toLowerCase().includes('rear') ||
                d.label.toLowerCase().includes('environment')
            );
            if (rearIdx >= 0) currentCamIdx = rearIdx;
            setStatus('idle', cameras.length + ' kamera ditemukan — siap digunakan');
        } else {
            setStatus('error', 'Tidak ada kamera ditemukan di perangkat ini');
            btnStart.disabled = true;
            btnStart.style.opacity = '.5';
        }
    }).catch(err => {
        console.warn('Camera enum error:', err);
        // Still allow trying — browser will ask on start
        setStatus('idle', 'Siap — tekan tombol untuk mulai scan');
    });

});
</script>
@endpush
