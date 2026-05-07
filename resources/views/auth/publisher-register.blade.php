@if(auth()->check())
    <script>window.location="{{ auth()->user()->role === 'publisher' ? route('publisher.dashboard') : route('member.dashboard') }}";</script>
@endif
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Publisher - LOA SIPTENAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .register-header h1 {
            margin: 0;
            font-weight: 300;
            font-size: 2rem;
        }
        
        .register-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .register-body {
            padding: 40px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .form-section h4 {
            color: #667eea;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .text-muted small {
            font-size: 0.875rem;
        }
        
        .agreement-box {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .invalid-feedback {
            display: block;
            font-size: 0.875rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <h1><i class="fas fa-building me-3"></i>Publisher Registration</h1>
                <p>Bergabung sebagai publisher di LOA SIPTENAN untuk mengelola jurnal dan LOA</p>
            </div>

            <!-- Body -->
            <div class="register-body">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('publisher.register') }}" method="POST" autocomplete="off">
                    @csrf
                    {{-- Honeypot: bot akan mengisi field ini, manusia tidak --}}
                    <div style="display:none" aria-hidden="true">
                        <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-user me-2"></i>Informasi Pribadi</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Nama lengkap Anda">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required
                                       placeholder="+62 812-3456-7890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required
                                   placeholder="your.email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Email ini akan digunakan untuk login ke sistem</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" required autocomplete="new-password"
                                           placeholder="Minimal 8 karakter">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePass('password',this)"><i class="fas fa-eye"></i></button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div id="pass-strength" class="mt-1" style="font-size:.8rem"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                           placeholder="Ulangi password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePass('password_confirmation',this)"><i class="fas fa-eye"></i></button>
                                </div>
                                <div id="pass-match" class="mt-1" style="font-size:.8rem"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-building me-2"></i>Informasi Perusahaan/Institusi</h4>
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label fw-bold">Nama Perusahaan/Institusi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                   id="company_name" name="company_name" value="{{ old('company_name') }}" required
                                   placeholder="PT. Academic Publisher Indonesia">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="company_address" class="form-label fw-bold">Alamat Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                      id="company_address" name="company_address" rows="3" required
                                      placeholder="Alamat lengkap perusahaan/institusi">{{ old('company_address') }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_phone" class="form-label fw-bold">Telepon Perusahaan <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('company_phone') is-invalid @enderror" 
                                       id="company_phone" name="company_phone" value="{{ old('company_phone') }}" required
                                       placeholder="+62 21-1234-5678">
                                @error('company_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label fw-bold">Email Perusahaan <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('company_email') is-invalid @enderror" 
                                       id="company_email" name="company_email" value="{{ old('company_email') }}" required
                                       placeholder="info@company.com">
                                @error('company_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company_website" class="form-label fw-bold">Website Perusahaan</label>
                            <input type="url" class="form-control @error('company_website') is-invalid @enderror" 
                                   id="company_website" name="company_website" value="{{ old('company_website') }}"
                                   placeholder="https://www.company.com">
                            @error('company_website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opsional - Website perusahaan/institusi</small>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="agreement-box">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('terms_agreement') is-invalid @enderror" 
                                   id="terms_agreement" name="terms_agreement" value="1" {{ old('terms_agreement') ? 'checked' : '' }} required>
                            <label class="form-check-label" for="terms_agreement">
                                <strong>Saya menyetujui <a href="#" class="text-primary">Syarat dan Ketentuan</a> serta <a href="#" class="text-primary">Kebijakan Privasi</a></strong>
                            </label>
                            @error('terms_agreement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Dengan mendaftar, Anda setuju untuk menggunakan platform ini sesuai dengan ketentuan yang berlaku.
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Daftar sebagai Publisher
                    </button>

                    <!-- Login Link -->
                    <div class="login-link">
                        <p class="text-muted mb-0">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold">Login di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        }

        // Password strength
        document.getElementById('password').addEventListener('input', function() {
            const v = this.value;
            const el = document.getElementById('pass-strength');
            let score = 0;
            if (v.length >= 8) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;
            const labels = ['', '<span style="color:#ef4444">Sangat lemah</span>', '<span style="color:#f59e0b">Lemah</span>', '<span style="color:#3b82f6">Cukup</span>', '<span style="color:#22c55e">Kuat ✓</span>'];
            el.innerHTML = v.length ? labels[score] || labels[1] : '';
            checkMatch();
        });

        // Password match check
        document.getElementById('password_confirmation').addEventListener('input', checkMatch);
        function checkMatch() {
            const p = document.getElementById('password').value;
            const c = document.getElementById('password_confirmation').value;
            const el = document.getElementById('pass-match');
            if (!c) { el.innerHTML = ''; return; }
            if (p === c) {
                el.innerHTML = '<span style="color:#22c55e"><i class="fas fa-check me-1"></i>Password cocok</span>';
                document.getElementById('password_confirmation').setCustomValidity('');
            } else {
                el.innerHTML = '<span style="color:#ef4444"><i class="fas fa-times me-1"></i>Password tidak cocok</span>';
                document.getElementById('password_confirmation').setCustomValidity('Password tidak cocok');
            }
        }

        // Auto-format phone — hanya format saat blur (bukan setiap keystroke)
        function formatPhone(input) {
            input.addEventListener('blur', function() {
                let v = this.value.trim().replace(/\s+/g, '');
                if (!v) return;
                // Hapus semua karakter selain angka dan +
                let digits = v.replace(/[^\d]/g, '');
                if (digits.startsWith('0')) {
                    this.value = '+62' + digits.substring(1);
                } else if (digits.startsWith('62')) {
                    this.value = '+' + digits;
                } else if (!v.startsWith('+')) {
                    this.value = '+62' + digits;
                }
            });
        }
        formatPhone(document.getElementById('phone'));
        formatPhone(document.getElementById('company_phone'));
    </script>
</body>
</html>
