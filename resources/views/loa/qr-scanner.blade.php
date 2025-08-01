@extends('layouts.app')

@section('title', 'Scan QR Code LOA - LOA Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>
                        Scan QR Code LOA
                    </h4>
                    <p class="mb-0 mt-2">Scan QR Code pada dokumen LOA untuk verifikasi langsung</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- QR Scanner Container -->
                    <div class="text-center mb-4">
                        <div id="qr-scanner" style="width: 100%; max-width: 400px; margin: 0 auto;">
                            <video id="preview" style="width: 100%; border-radius: 8px; border: 2px solid #dee2e6;"></video>
                        </div>
                        <div class="mt-3">
                            <button id="start-scan" class="btn btn-success me-2">
                                <i class="fas fa-play me-1"></i>
                                Mulai Scan
                            </button>
                            <button id="stop-scan" class="btn btn-danger me-2" style="display: none;">
                                <i class="fas fa-stop me-1"></i>
                                Stop Scan
                            </button>
                            <button id="switch-camera" class="btn btn-info" style="display: none;">
                                <i class="fas fa-sync-alt me-1"></i>
                                Ganti Kamera
                            </button>
                        </div>
                    </div>

                    <!-- Result Display -->
                    <div id="scan-result" style="display: none;">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>QR Code Terdeteksi!</h5>
                            <p class="mb-2">Kode LOA: <strong id="detected-code"></strong></p>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button id="verify-btn" class="btn btn-primary">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Verifikasi LOA
                                </button>
                                <button id="scan-again" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-1"></i>
                                    Scan Lagi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Input Alternative -->
                    <div class="border-top pt-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-keyboard me-1"></i>
                            Atau masukkan kode LOA secara manual:
                        </h6>
                        <form action="{{ route('loa.check') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       name="loa_code" 
                                       placeholder="Masukkan kode LOA (contoh: LOA20250801001)"
                                       required>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-search me-1"></i>
                                    Verifikasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        Cara Menggunakan QR Scanner
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Langkah-langkah:</h6>
                            <ol class="list-unstyled">
                                <li><i class="fas fa-play text-success me-2"></i>Klik "Mulai Scan"</li>
                                <li><i class="fas fa-mobile-alt text-success me-2"></i>Izinkan akses kamera</li>
                                <li><i class="fas fa-qrcode text-success me-2"></i>Arahkan ke QR Code LOA</li>
                                <li><i class="fas fa-check text-success me-2"></i>Tunggu deteksi otomatis</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Tips:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Pastikan pencahayaan cukup</li>
                                <li><i class="fas fa-mobile-alt text-warning me-2"></i>Jaga jarak yang tepat</li>
                                <li><i class="fas fa-hand-paper text-warning me-2"></i>Tahan kamera dengan stabil</li>
                                <li><i class="fas fa-focus text-warning me-2"></i>QR Code dalam frame jelas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('loa.verify') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-keyboard me-1"></i>
                    Input Manual
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

@push('scripts')
<script src="https://unpkg.com/instascan@1.0.0/instascan.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let scanner = null;
    let currentCamera = 0;
    let cameras = [];

    const preview = document.getElementById('preview');
    const startBtn = document.getElementById('start-scan');
    const stopBtn = document.getElementById('stop-scan');
    const switchBtn = document.getElementById('switch-camera');
    const scanResult = document.getElementById('scan-result');
    const detectedCode = document.getElementById('detected-code');
    const verifyBtn = document.getElementById('verify-btn');
    const scanAgainBtn = document.getElementById('scan-again');

    // Initialize scanner
    function initScanner() {
        scanner = new Instascan.Scanner({ 
            video: preview,
            scanPeriod: 5,
            mirror: false
        });

        scanner.addListener('scan', function(content) {
            console.log('QR Code detected:', content);
            handleScanResult(content);
        });

        // Get cameras
        Instascan.Camera.getCameras().then(function(cameraList) {
            cameras = cameraList;
            if (cameras.length > 0) {
                console.log('Cameras found:', cameras.length);
                if (cameras.length > 1) {
                    switchBtn.style.display = 'inline-block';
                }
            } else {
                alert('Tidak ada kamera yang ditemukan.');
            }
        }).catch(function(e) {
            console.error('Error accessing cameras:', e);
            alert('Error mengakses kamera: ' + e.message);
        });
    }

    // Handle scan result
    function handleScanResult(content) {
        // Extract LOA code from URL or direct code
        let loaCode = content;
        
        // If it's a URL, extract the LOA code
        if (content.includes('/verify-qr/')) {
            const urlParts = content.split('/verify-qr/');
            if (urlParts.length > 1) {
                loaCode = urlParts[1];
            }
        }

        // Validate LOA code format
        if (loaCode.match(/^LOA\d{8}\d{3}$/)) {
            detectedCode.textContent = loaCode;
            scanResult.style.display = 'block';
            stopScanning();
            
            // Set verify button action
            verifyBtn.onclick = function() {
                window.location.href = '/verify-qr/' + loaCode;
            };
        } else {
            alert('QR Code tidak valid atau bukan QR Code LOA.');
        }
    }

    // Start scanning
    function startScanning() {
        if (cameras.length > 0) {
            scanner.start(cameras[currentCamera]);
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
            scanResult.style.display = 'none';
        } else {
            alert('Tidak ada kamera yang tersedia.');
        }
    }

    // Stop scanning
    function stopScanning() {
        if (scanner) {
            scanner.stop();
        }
        startBtn.style.display = 'inline-block';
        stopBtn.style.display = 'none';
    }

    // Switch camera
    function switchCamera() {
        if (cameras.length > 1) {
            currentCamera = (currentCamera + 1) % cameras.length;
            if (scanner) {
                scanner.stop();
                setTimeout(() => {
                    scanner.start(cameras[currentCamera]);
                }, 100);
            }
        }
    }

    // Event listeners
    startBtn.addEventListener('click', startScanning);
    stopBtn.addEventListener('click', stopScanning);
    switchBtn.addEventListener('click', switchCamera);
    scanAgainBtn.addEventListener('click', function() {
        scanResult.style.display = 'none';
        startScanning();
    });

    // Initialize
    initScanner();
});
</script>
@endpush
