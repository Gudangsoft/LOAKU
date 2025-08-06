<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin - LOA SIPTENAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-color: #1f2937;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: var(--primary-gradient);
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            z-index: 0;
            animation: backgroundMove 20s ease-in-out infinite;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-20px, -20px); }
        }

        .register-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-wrapper {
            max-width: 1000px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            min-height: 700px;
        }

        @media (max-width: 968px) {
            .register-wrapper {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
        }

        /* Left Panel - Info */
        .info-panel {
            background: rgba(255, 255, 255, 0.1);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .info-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--secondary-gradient);
            opacity: 0.1;
            border-radius: 50%;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .info-content {
            position: relative;
            z-index: 2;
        }

        .info-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            animation: pulse 2s ease-in-out infinite alternate;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }

        .info-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, rgba(255, 255, 255, 0.8));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-description {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            text-align: left;
            max-width: 280px;
        }

        .benefits-list li {
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .benefits-list i {
            color: var(--success-color);
            background: rgba(16, 185, 129, 0.2);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* Right Panel - Register Form */
        .register-panel {
            background: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            background: rgba(239, 68, 68, 0.05);
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .role-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            display: none;
        }

        .role-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .role-card:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .role-option input[type="radio"]:checked + .role-card {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            color: #4f46e5;
        }

        .role-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .role-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .role-description {
            font-size: 0.75rem;
            color: #6b7280;
            line-height: 1.3;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-meter {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: var(--danger-color);
            transition: all 0.3s ease;
        }

        .strength-fill.weak { 
            width: 25%; 
            background: var(--danger-color); 
        }
        
        .strength-fill.fair { 
            width: 50%; 
            background: var(--warning-color); 
        }
        
        .strength-fill.good { 
            width: 75%; 
            background: #3b82f6; 
        }
        
        .strength-fill.strong { 
            width: 100%; 
            background: var(--success-color); 
        }

        .strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            color: #6b7280;
        }

        .btn-register {
            width: 100%;
            padding: 1rem;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .register-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .register-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }

        .register-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .register-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Loading state */
        .btn-loading {
            position: relative;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .btn-loading span {
            opacity: 0;
        }

        /* Mobile adjustments */
        @media (max-width: 968px) {
            .info-panel {
                display: none;
            }

            .register-container {
                padding: 1rem;
            }

            .register-panel {
                padding: 2rem;
            }

            .register-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 600px) {
            .role-selection {
                grid-template-columns: 1fr;
            }

            .register-panel {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-wrapper">
            <!-- Info Panel -->
            <div class="info-panel">
                <div class="info-content">
                    <div class="info-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="info-title">Bergabung dengan LOA SIPTENAN</h1>
                    <p class="info-description">
                        Dapatkan akses ke sistem manajemen Letter of Acceptance terdepan untuk publikasi ilmiah
                    </p>
                    
                    <ul class="benefits-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Dashboard analitik lengkap</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Manajemen LOA otomatis</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Verifikasi QR Code real-time</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Integrasi jurnal dan penerbit</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Laporan dan statistik</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Support 24/7</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Register Panel -->
            <div class="register-panel">
                <div class="register-header">
                    <h2 class="register-title">Buat Akun Admin</h2>
                    <p class="register-subtitle">Lengkapi informasi di bawah untuk membuat akun admin baru</p>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Ada beberapa kesalahan dalam form. Silakan periksa kembali.
                    </div>
                @endif

                <form method="POST" action="{{ url('/admin/register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Name -->
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <div class="form-control-wrapper">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name">
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="form-control-wrapper">
                            <i class="fas fa-envelope form-icon"></i>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   placeholder="admin@example.com"
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="form-group">
                        <label class="form-label">Pilih Role</label>
                        <div class="role-selection">
                            <div class="role-option">
                                <input type="radio" id="role_administrator" name="role" value="administrator" 
                                       {{ old('role') == 'administrator' ? 'checked' : '' }} required>
                                <label for="role_administrator" class="role-card">
                                    <i class="fas fa-crown role-icon"></i>
                                    <div class="role-title">Administrator</div>
                                    <div class="role-description">Akses penuh ke semua fitur dan pengaturan sistem</div>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="role_member" name="role" value="member" 
                                       {{ old('role') == 'member' ? 'checked' : '' }} required>
                                <label for="role_member" class="role-card">
                                    <i class="fas fa-user role-icon"></i>
                                    <div class="role-title">Member</div>
                                    <div class="role-description">Akses terbatas untuk mengelola LOA dan jurnal</div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="form-control-wrapper">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Minimal 8 karakter"
                                   required 
                                   autocomplete="new-password">
                        </div>
                        <div class="password-strength">
                            <div class="strength-meter">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Masukkan password untuk melihat kekuatan</div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                        <div class="form-control-wrapper">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi password"
                                   required 
                                   autocomplete="new-password">
                        </div>
                        <div id="passwordMatch" class="invalid-feedback" style="display: none;">
                            Password tidak sama
                        </div>
                    </div>

                    <button type="submit" class="btn-register" id="registerBtn">
                        <span>
                            <i class="fas fa-user-plus me-2"></i>
                            Buat Akun Admin
                        </span>
                    </button>
                </form>

                <div class="register-footer">
                    <div class="register-links">
                        <a href="{{ url('/admin/login') }}" class="register-link">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Sudah punya akun? Masuk sekarang
                        </a>
                        <a href="{{ route('home') }}" class="register-link">
                            <i class="fas fa-home me-2"></i>
                            Kembali ke website utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            let score = 0;
            let feedback = '';

            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            strengthFill.className = 'strength-fill';
            
            if (password.length === 0) {
                feedback = 'Masukkan password untuk melihat kekuatan';
            } else if (score <= 2) {
                strengthFill.classList.add('weak');
                feedback = 'Password lemah - gunakan kombinasi huruf, angka, dan simbol';
            } else if (score === 3) {
                strengthFill.classList.add('fair');
                feedback = 'Password cukup - tambahkan karakter khusus untuk keamanan lebih baik';
            } else if (score === 4) {
                strengthFill.classList.add('good');
                feedback = 'Password bagus - hampir sempurna!';
            } else {
                strengthFill.classList.add('strong');
                feedback = 'Password sangat kuat - keamanan terjamin!';
            }

            strengthText.textContent = feedback;
        });

        // Password confirmation checker
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmation && password !== confirmation) {
                matchDiv.style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                matchDiv.style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });

        // Form submission with loading state
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== confirmation) {
                e.preventDefault();
                document.getElementById('passwordMatch').style.display = 'block';
                document.getElementById('password_confirmation').classList.add('is-invalid');
                return;
            }

            const btn = document.getElementById('registerBtn');
            btn.classList.add('btn-loading');
            btn.disabled = true;
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Add input focus effects
        document.querySelectorAll('.form-control').forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>
