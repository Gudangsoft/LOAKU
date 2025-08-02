<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LOA Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #0066cc !important;
        }
        
        /* Admin Navbar Styling */
        .navbar.admin-navbar {
            background: linear-gradient(45deg, #2c3e50, #34495e) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-navbar .navbar-brand {
            color: #fff !important;
            font-size: 1.3rem;
        }
        
        .admin-navbar .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 0 2px;
            padding: 8px 12px !important;
        }
        
        .admin-navbar .nav-link:hover {
            color: #fff !important;
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        
        .admin-navbar .dropdown-menu {
            background-color: #34495e;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .admin-navbar .dropdown-item {
            color: rgba(255,255,255,0.9) !important;
        }
        
        .admin-navbar .dropdown-item:hover {
            background-color: #2c3e50;
            color: #fff !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #764ba2, #667eea);
        }
        .stats-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm @auth @if(auth()->user()->is_admin ?? false) admin-navbar @endif @endauth">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-certificate me-2"></i>
                LOA Management System
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    @if(auth()->user()->is_admin ?? false)
                        <!-- Menu khusus untuk Admin -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.loa-requests.index') }}">
                                    <i class="fas fa-file-alt me-1"></i>Permohonan LOA
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('loa.validated') }}">
                                    <i class="fas fa-certificate me-1"></i>LOA Tervalidasi
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-1"></i>Kelola Data
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.journals.index') }}">
                                        <i class="fas fa-book me-2"></i>Jurnal
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.publishers.index') }}">
                                        <i class="fas fa-building me-2"></i>Penerbit
                                    </a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i>Lihat Website
                                </a>
                            </li>
                        </ul>
                    @else
                        <!-- Menu untuk User biasa -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('loa.create') }}">Request LOA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('loa.validated') }}">Cari & Download LOA</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-shield-alt"></i> Verifikasi
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('qr.scanner') }}">
                                        <i class="fas fa-qrcode me-2"></i>Scan QR Code
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('loa.verify') }}">
                                        <i class="fas fa-keyboard me-2"></i>Input Manual
                                    </a></li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                @else
                    <!-- Menu untuk Guest -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('loa.create') }}">Request LOA</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('loa.validated') }}">Cari & Download LOA</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-shield-alt"></i> Verifikasi
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('qr.scanner') }}">
                                    <i class="fas fa-qrcode me-2"></i>Scan QR Code
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('loa.verify') }}">
                                    <i class="fas fa-keyboard me-2"></i>Input Manual
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                @endauth
                
                <!-- Admin Menu -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-cog"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @auth
                                @if(auth()->user()->is_admin ?? false)
                                    <!-- Menu untuk admin yang sudah login -->
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.loa-requests.index') }}">
                                        <i class="fas fa-file-alt me-2"></i>LOA Requests
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('loa.validated') }}">
                                        <i class="fas fa-certificate me-2"></i>LOA Tervalidasi
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.journals.index') }}">
                                        <i class="fas fa-book me-2"></i>Journals
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                                @endif
                            @else
                                <!-- Menu untuk guest -->
                                <li><a class="dropdown-item" href="{{ route('admin.login') }}">
                                    <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                                </a></li>
                                <li><a class="dropdown-item" href="/admin">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.create-admin') }}">
                                    <i class="fas fa-user-plus me-2"></i>Create Admin
                                </a></li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>LOA Management System</h5>
                    <p>Sistem manajemen pengelolaan LOA untuk artikel jurnal ilmiah dengan teknologi modern dan user-friendly.</p>
                </div>
                <div class="col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('loa.create') }}" class="text-light">Request LOA</a></li>
                        <li><a href="{{ route('loa.validated') }}" class="text-light">Cari & Download LOA</a></li>
                        <li><a href="{{ route('loa.verify') }}" class="text-light">Verifikasi LOA</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} LOA Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
