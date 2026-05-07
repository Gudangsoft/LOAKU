<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LOA SIPTENAN')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:     #4F46E5;
            --primary-dark:#3730A3;
            --secondary:   #06B6D4;
            --accent:      #10B981;
            --warning:     #F59E0B;
            --danger:      #EF4444;
            --bg-light:    #F8FAFC;
            --surface:     #FFFFFF;
            --dark:        #1E293B;
            --text:        #334155;
            --text-muted:  #64748B;
            --border:      #E2E8F0;
            --shadow-sm:   0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow:      0 4px 16px rgba(0,0,0,.08);
            --shadow-lg:   0 10px 40px rgba(0,0,0,.12);
            --radius:      12px;
            --radius-lg:   20px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text);
            line-height: 1.6;
        }

        /* ── Navbar ── */
        .navbar {
            background: var(--surface) !important;
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--dark) !important;
            text-decoration: none;
        }

        .navbar-brand .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .navbar-brand .brand-text span {
            display: block;
            font-size: 0.65rem;
            font-weight: 500;
            color: var(--text-muted);
            line-height: 1;
        }

        .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text) !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: 8px;
            transition: background .2s, color .2s;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--bg-light);
            color: var(--primary) !important;
        }

        .nav-link.active {
            color: var(--primary) !important;
            font-weight: 600;
        }

        .navbar .btn-nav-cta {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white !important;
            border: none;
            padding: 0.45rem 1.1rem !important;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: opacity .2s, transform .2s;
        }

        .navbar .btn-nav-cta:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text);
            transition: background .15s;
        }

        .dropdown-item:hover { background: var(--bg-light); color: var(--primary); }
        .dropdown-item.text-danger:hover { background: #FEE2E2; }

        .dropdown-divider { border-color: var(--border); margin: 0.4rem 0; }

        /* ── Alert strip ── */
        .alert-strip {
            border-radius: 0;
            border: none;
            border-left: 4px solid;
            margin: 0;
            padding: 0.8rem 0;
        }
        .alert-strip.alert-success { border-left-color: var(--accent); background: #ECFDF5; color: #065F46; }
        .alert-strip.alert-danger  { border-left-color: var(--danger);  background: #FEF2F2; color: #991B1B; }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            transition: box-shadow .25s, transform .25s;
        }
        .card:hover { box-shadow: var(--shadow); }

        /* ── Buttons ── */
        .btn { font-weight: 600; border-radius: 8px; font-size: 0.9rem; transition: all .2s; }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #6D28D9 100%);
            border: none;
            color: white;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(79,70,229,.4); background: linear-gradient(135deg, var(--primary-dark) 0%, #5B21B6 100%); }

        .btn-success {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            border: none;
            color: white;
        }
        .btn-success:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(16,185,129,.4); }

        .btn-outline-primary { border: 2px solid var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: white; transform: translateY(-1px); }

        /* ── Footer ── */
        .site-footer {
            background: var(--dark);
            color: #CBD5E1;
            padding: 56px 0 0;
            margin-top: 80px;
        }

        .site-footer h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 1.2rem;
            font-size: 1rem;
        }

        .site-footer p { font-size: 0.875rem; line-height: 1.7; }

        .site-footer a {
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color .2s;
        }

        .site-footer a:hover { color: white; }

        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 0.6rem; }
        .footer-links li a::before { content: '→ '; font-size: 0.75rem; }

        .footer-brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .footer-divider {
            border-top: 1px solid #334155;
            margin-top: 40px;
            padding: 20px 0;
        }

        .footer-bottom {
            font-size: 0.8rem;
            color: #64748B;
        }

        .social-icons a {
            width: 34px;
            height: 34px;
            background: #334155;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            transition: background .2s, color .2s;
            font-size: 0.85rem;
        }

        .social-icons a:hover { background: var(--primary); color: white; }

        /* ── Utility ── */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .text-primary { color: var(--primary) !important; }
        .bg-primary   { background-color: var(--primary) !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-light); }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

    </style>
    @stack('styles')
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="brand-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="brand-text">
                    LOA SIPTENAN
                    <span>Sistem Manajemen LOA</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1" style="font-size:.8rem"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('loa.create') ? 'active' : '' }}" href="{{ route('loa.create') }}">
                            <i class="fas fa-plus-circle me-1" style="font-size:.8rem"></i> Request LOA
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('loa.search') ? 'active' : '' }}" href="{{ route('loa.search') }}">
                            <i class="fas fa-search me-1" style="font-size:.8rem"></i> Cari LOA
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('loa.verify') ? 'active' : '' }}" href="{{ route('loa.verify') }}">
                            <i class="fas fa-shield-check me-1" style="font-size:.8rem"></i> Verifikasi
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('qr.scanner') }}">
                            <i class="fas fa-qrcode me-1"></i> Scan QR
                        </a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                                <div style="width:30px;height:30px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:.75rem;font-weight:700;">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->is_admin ?? false)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Admin
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2 text-primary"></i>Profil Saya
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> Masuk
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.login') }}">
                                    <i class="fas fa-user-shield me-2 text-primary"></i>Login Admin
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('publisher.register.form') }}">
                                    <i class="fas fa-user-plus me-2" style="color:var(--accent)"></i>Daftar Publisher
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('loa.create') }}" class="btn btn-nav-cta nav-link">
                                <i class="fas fa-paper-plane me-1"></i> Request LOA
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-strip alert-success alert-dismissible fade show" role="alert">
            <div class="container d-flex align-items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:.75rem"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-strip alert-danger alert-dismissible fade show" role="alert">
            <div class="container d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:.75rem"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h5>LOA SIPTENAN</h5>
                    <p>Sistem Pengelolaan Letter of Acceptance (LOA) untuk publikasi artikel jurnal ilmiah. Mudah, cepat, dan terpercaya.</p>
                    <div class="social-icons d-flex gap-2 mt-3">
                        <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
                        <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" title="Website"><i class="fas fa-globe"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <h5>Layanan</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('loa.create') }}">Request LOA</a></li>
                        <li><a href="{{ route('loa.search') }}">Cari LOA</a></li>
                        <li><a href="{{ route('loa.verify') }}">Verifikasi LOA</a></li>
                        <li><a href="{{ route('qr.scanner') }}">Scan QR Code</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6">
                    <h5>Akun</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('admin.login') }}">Login Admin</a></li>
                        <li><a href="{{ route('publisher.register.form') }}">Daftar Publisher</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h5>Tentang Sistem</h5>
                    <p>LOA SIPTENAN adalah platform digital untuk mengelola proses pengajuan, validasi, dan penerbitan Letter of Acceptance jurnal ilmiah secara efisien.</p>
                    <div class="d-flex align-items-center gap-2 mt-3" style="font-size:.8rem;color:#94A3B8">
                        <i class="fas fa-shield-alt" style="color:var(--accent)"></i>
                        Dokumen terverifikasi dengan QR Code unik
                    </div>
                </div>
            </div>

            <div class="footer-divider d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="footer-bottom">
                    &copy; {{ date('Y') }} LOA SIPTENAN. Seluruh hak cipta dilindungi.
                </div>
                <div class="footer-bottom">
                    Dibangun dengan <i class="fas fa-heart" style="color:#EF4444;font-size:.75rem"></i> untuk kemajuan publikasi ilmiah Indonesia
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide alerts
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
