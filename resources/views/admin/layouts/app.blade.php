<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - LOA SIPTENAN System</title>
    
    <!-- Static favicon for now -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #5e72e4 0%, #825ee4 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 0.35rem;
            margin: 0.25rem 0;
            transition: all 0.15s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 1rem;
        }
        
        .sidebar-brand {
            color: #fff;
            padding: 1.5rem;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.25rem;
        }
        
        .sidebar-brand:hover {
            color: #fff;
        }
        
        .main-content {
            background-color: #fff;
            min-height: 100vh;
        }
        
        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.5rem;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .text-xs {
            font-size: 0.75rem;
        }
        
        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            vertical-align: middle;
        }
        
        .card {
            transition: all 0.15s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        /* Debug CSS untuk menu settings */
        .sidebar .nav-item {
            display: block !important;
            visibility: visible !important;
        }
        
        .sidebar-heading {
            display: block !important;
            visibility: visible !important;
            color: rgba(255,255,255,0.6) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="position-sticky">
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">LOA Admin</div>
                    </a>
                    
                    <hr class="sidebar-divider my-3" style="border-color: rgba(255,255,255,0.15);">
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.loa-requests.*') ? 'active' : '' }}" href="{{ route('admin.loa-requests.index') }}">
                                <i class="fas fa-file-alt"></i>
                                <span>Permohonan LOA</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('loa.validated') ? 'active' : '' }}" href="{{ route('loa.validated') }}">
                                <i class="fas fa-certificate"></i>
                                <span>LOA Tervalidasi</span>
                            </a>
                        </li>
                        
                        <hr class="sidebar-divider my-3" style="border-color: rgba(255,255,255,0.15);">
                        
                        <!-- Kelola Data Section -->
                        <div class="sidebar-heading" style="color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1rem; margin-bottom: 0.5rem; padding: 0 1rem;">
                            Kelola Data
                        </div>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.journals.*') ? 'active' : '' }}" href="{{ route('admin.journals.index') }}">
                                <i class="fas fa-book"></i>
                                <span>Kelola Jurnal</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}" href="{{ route('admin.publishers.index') }}">
                                <i class="fas fa-building"></i>
                                <span>Kelola Penerbit</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.loa-templates.*') ? 'active' : '' }}" href="{{ route('admin.loa-templates.index') }}">
                                <i class="fas fa-file-code"></i>
                                <span>Template LOA</span>
                            </a>
                        </li>
                        
                        <hr class="sidebar-divider my-3" style="border-color: rgba(255,255,255,0.15);">
                        
                        <!-- Pengaturan Section -->
                        <div style="color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1rem; margin-bottom: 0.5rem; padding: 0 1rem; margin-top: 1rem;">
                            🔧 PENGATURAN ADMIN
                        </div>
                        
                        <li class="nav-item" style="display: block !important;">
                            <a class="nav-link {{ request()->routeIs('admin.website-settings.*') ? 'active' : '' }}" href="{{ route('admin.website-settings.index') }}" style="color: rgba(255, 255, 255, 0.9) !important; padding: 1rem 1.5rem !important; display: block !important;">
                                <i class="fas fa-cogs" style="margin-right: 0.75rem; color: #28a745;"></i>
                                <span style="font-weight: 600;">Website Settings</span>
                            </a>
                        </li>
                        
                        <li class="nav-item" style="display: block !important;">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}" style="color: rgba(255, 255, 255, 0.9) !important; padding: 1rem 1.5rem !important; display: block !important;">
                                <i class="fas fa-users" style="margin-right: 0.75rem; color: #007bff;"></i>
                                <span style="font-weight: 600;">Kelola User</span>
                            </a>
                        </li>
                        
                        <li class="nav-item" style="display: block !important;">
                            <a class="nav-link {{ request()->routeIs('admin.system-logs.*') ? 'active' : '' }}" href="{{ route('admin.system-logs.index') }}" style="color: rgba(255, 255, 255, 0.9) !important; padding: 1rem 1.5rem !important; display: block !important;">
                                <i class="fas fa-list-alt" style="margin-right: 0.75rem; color: #ffc107;"></i>
                                <span style="font-weight: 600;">System Logs</span>
                            </a>
                        </li>
                        
                        <li class="nav-item" style="display: block !important;">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}" style="color: rgba(255, 255, 255, 0.9) !important; padding: 1rem 1.5rem !important; display: block !important;">
                                <i class="fas fa-chart-bar" style="margin-right: 0.75rem; color: #17a2b8;"></i>
                                <span style="font-weight: 600;">System Status</span>
                            </a>
                        </li>
                        
                        <hr class="sidebar-divider my-3" style="border-color: rgba(255,255,255,0.15);">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('loa.search') }}" target="_blank">
                                <i class="fas fa-search"></i>
                                <span>Cari LOA</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="fas fa-home"></i>
                                <span>Lihat Website</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto main-content">
                <!-- Topbar -->
                <div class="topbar d-flex justify-content-between align-items-center">
                    <button class="btn btn-link d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="d-none d-lg-inline-block">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                    <i class="fas fa-home me-2"></i>Lihat Website
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
