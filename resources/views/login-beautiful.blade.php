<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LOAKU System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .user-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .user-card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .user-card.selected {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        .role-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="login-card p-5">
                    <div class="text-center mb-5">
                        <div class="mb-3">
                            <i class="fas fa-university text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-2">LOAKU System</h2>
                        <p class="text-muted">Letter of Acceptance Management System</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Akun Pengguna</label>
                            <div class="row g-3">
                                <!-- Admin User -->
                                <div class="col-md-6">
                                    <div class="user-card card h-100" data-email="admin@loaku.test">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-user-shield text-danger" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="card-title mb-2">Admin LOAKU</h6>
                                            <p class="card-text small text-muted mb-2">admin@loaku.test</p>
                                            <span class="badge bg-danger role-badge">Administrator</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Publisher 1 -->
                                <div class="col-md-6">
                                    <div class="user-card card h-100" data-email="publisher1@loaku.test">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-user-edit text-primary" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="card-title mb-2">Publisher Satu</h6>
                                            <p class="card-text small text-muted mb-2">publisher1@loaku.test</p>
                                            <span class="badge bg-primary role-badge">Publisher</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Publisher 2 -->
                                <div class="col-md-6">
                                    <div class="user-card card h-100" data-email="publisher2@loaku.test">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-user-edit text-success" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="card-title mb-2">Publisher Dua</h6>
                                            <p class="card-text small text-muted mb-2">publisher2@loaku.test</p>
                                            <span class="badge bg-success role-badge">Publisher</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- User LOAKU -->
                                <div class="col-md-6">
                                    <div class="user-card card h-100" data-email="user@loaku.test">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-user text-info" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="card-title mb-2">User LOAKU</h6>
                                            <p class="card-text small text-muted mb-2">user@loaku.test</p>
                                            <span class="badge bg-info role-badge">Publisher</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="email" id="selectedEmail" required>
                        <input type="hidden" name="password" value="password">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="loginBtn" disabled>
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </button>
                            <a href="/" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>
                                Kembali ke Home
                            </a>
                        </div>
                    </form>

                    <div class="mt-4 pt-4 border-top">
                        <h6 class="text-muted mb-3">Informasi Akses:</h6>
                        <div class="row text-sm">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="fas fa-user-shield text-danger me-2"></i>
                                    <strong>Admin:</strong> Kelola semua data sistem
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-user-edit text-primary me-2"></i>
                                    <strong>Publisher 1:</strong> Jurnal Teknologi Indonesia
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="fas fa-user-edit text-success me-2"></i>
                                    <strong>Publisher 2:</strong> Jurnal Sains dan Teknologi
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-user text-info me-2"></i>
                                    <strong>User LOAKU:</strong> Publisher umum
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.user-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.user-card').forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Set email value
                const email = this.getAttribute('data-email');
                document.getElementById('selectedEmail').value = email;
                
                // Enable login button
                document.getElementById('loginBtn').disabled = false;
            });
        });

        // Auto submit on card click (optional)
        document.querySelectorAll('.user-card').forEach(card => {
            card.addEventListener('dblclick', function() {
                document.getElementById('loginForm').submit();
            });
        });
    </script>
</body>
</html>
