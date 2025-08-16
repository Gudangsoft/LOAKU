<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success - LOA SIPTENAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-container {
            max-width: 600px;
            margin: 20px;
        }
        
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
        }
        
        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
        }
        
        .success-header .icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .success-body {
            padding: 40px;
        }
        
        .btn-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }
        
        .info-box {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .info-box h5 {
            color: #1976d2;
            margin-bottom: 15px;
        }
        
        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-box li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <!-- Header -->
            <div class="success-header">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Registrasi Berhasil!</h1>
                <p class="mb-0">Selamat datang di LOA SIPTENAN sebagai Publisher</p>
            </div>

            <!-- Body -->
            <div class="success-body">
                <h3 class="text-success mb-4">
                    <i class="fas fa-building me-2"></i>
                    Akun Publisher Anda Telah Aktif
                </h3>
                
                <p class="lead text-muted mb-4">
                    Terima kasih telah mendaftar sebagai publisher di LOA SIPTENAN. 
                    Akun Anda telah berhasil dibuat dan siap digunakan.
                </p>

                <!-- Information Box -->
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>Langkah Selanjutnya:</h5>
                    <ul>
                        <li><strong>Akses Dashboard:</strong> Klik tombol di bawah untuk masuk ke dashboard publisher</li>
                        <li><strong>Kelola Profile:</strong> Lengkapi informasi profile dan perusahaan Anda</li>
                        <li><strong>Tambah Jurnal:</strong> Mulai menambahkan jurnal yang akan Anda kelola</li>
                        <li><strong>Review LOA:</strong> Kelola permintaan LOA yang masuk untuk jurnal Anda</li>
                        <li><strong>Template LOA:</strong> Buat template LOA custom untuk jurnal Anda</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <a href="{{ route('publisher.dashboard') }}" class="btn-dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Masuk ke Publisher Dashboard
                    </a>
                    
                    <a href="{{ route('home') }}" class="btn-secondary">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Homepage
                    </a>
                </div>

                <!-- Support Information -->
                <div class="mt-5 pt-4 border-top">
                    <h6 class="text-muted">Butuh Bantuan?</h6>
                    <p class="text-muted mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        Hubungi support kami di: 
                        <a href="mailto:support@loasiptenan.com" class="text-primary">support@loasiptenan.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
