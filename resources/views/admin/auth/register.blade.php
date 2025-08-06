<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - LOA System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .register-body {
            padding: 40px 30px;
        }
        .form-floating .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
        }
        .form-floating .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .role-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .role-card.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }
        .role-card:hover {
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-user-plus fa-3x mb-3"></i>
                <h3 class="mb-1">Registrasi Akun</h3>
                <p class="mb-0 opacity-75">LOA Management System</p>
            </div>
            
            <div class="register-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0 list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ url('/admin/register') }}">
                    @csrf
                    
                    <!-- Name Input -->
                    <div class="form-floating mb-3">
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               placeholder="Nama Lengkap"
                               value="{{ old('name') }}"
                               required>
                        <label for="name">
                            <i class="fas fa-user me-2"></i>Nama Lengkap
                        </label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="form-floating mb-3">
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               placeholder="Email"
                               value="{{ old('email') }}"
                               required>
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-shield-alt me-2"></i>Pilih Role
                        </label>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="role-card" onclick="selectRole('member')">
                                    <input type="radio" id="role_member" name="role" value="member" class="d-none" {{ old('role', 'member') == 'member' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-user-edit fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Member (Editor Jurnal)</h6>
                                            <small class="text-muted">Kelola publisher, jurnal, dan validasi LOA jurnal sendiri</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="role-card" onclick="selectRole('administrator')">
                                    <input type="radio" id="role_administrator" name="role" value="administrator" class="d-none" {{ old('role') == 'administrator' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-user-shield fa-2x text-danger"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Administrator</h6>
                                            <small class="text-muted">Akses penuh ke semua fitur sistem</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('role')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-floating mb-3">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               required
                               minlength="8">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="form-floating mb-4">
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Konfirmasi Password"
                               required
                               minlength="8">
                        <label for="password_confirmation">
                            <i class="fas fa-lock me-2"></i>Konfirmasi Password
                        </label>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn btn-primary btn-register w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>
                        Daftar Sekarang
                    </button>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Sudah punya akun? 
                            <a href="{{ url('/admin/login') }}" class="text-decoration-none fw-bold">
                                Login di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function selectRole(role) {
            // Remove selected class from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById('role_' + role).checked = true;
        }

        // Initialize selected role on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole) {
                const card = selectedRole.closest('.role-card');
                if (card) {
                    card.classList.add('selected');
                }
            }
        });
    </script>
</body>
</html>
