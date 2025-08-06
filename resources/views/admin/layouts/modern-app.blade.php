<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LOA SIPTENAN - Admin Dashboard">
    <meta name="author" content="SIPTENAN Team">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - LOA SIPTENAN</title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Admin Styles -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-header .logo {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .sidebar-header .brand-text {
            font-size: 1.2rem;
            font-weight: 700;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }

        /* Header */
        .header {
            background: white;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }

        .breadcrumb-container {
            margin-left: 1rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item {
            color: #6b7280;
        }

        .breadcrumb-item.active {
            color: var(--dark-color);
            font-weight: 500;
        }

        /* Header Right */
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-box {
            position: relative;
            display: none;
        }

        @media (min-width: 768px) {
            .search-box {
                display: block;
            }
        }

        .search-box input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            width: 300px;
            font-size: 0.9rem;
        }

        .search-box .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        /* Notifications */
        .notification-bell {
            position: relative;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .notification-bell:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0.2rem;
            right: 0.2rem;
            width: 8px;
            height: 8px;
            background: var(--danger-color);
            border-radius: 50%;
            border: 2px solid white;
        }

        /* User Dropdown */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: #f3f4f6;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info {
            display: none;
        }

        @media (min-width: 768px) {
            .user-info {
                display: block;
            }
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark-color);
            margin: 0;
        }

        .user-role {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
        }

        /* Content Area */
        .content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
        }

        .stats-card.success::before {
            background: var(--success-color);
        }

        .stats-card.warning::before {
            background: var(--warning-color);
        }

        .stats-card.danger::before {
            background: var(--danger-color);
        }

        .stats-card.info::before {
            background: var(--info-color);
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: var(--primary-color);
        }

        .stats-icon.success {
            background: var(--success-color);
        }

        .stats-icon.warning {
            background: var(--warning-color);
        }

        .stats-icon.danger {
            background: var(--danger-color);
        }

        .stats-icon.info {
            background: var(--info-color);
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .stats-label {
            font-size: 0.9rem;
            color: #6b7280;
            margin: 0;
        }

        .stats-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 0 1rem;
            }

            .content {
                padding: 1rem;
            }
        }

        /* Dark mode toggle */
        .dark-mode-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .dark-mode-toggle:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }

        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Toast notifications */
        .toast {
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="brand-text">LOA SIPTENAN</div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.loa-requests.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.loa-requests.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span class="nav-text">Permintaan LOA</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.journals.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.journals.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span class="nav-text">Jurnal</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.publishers.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span class="nav-text">Penerbit</span>
                    </a>
                </div>

                @if(Auth::user() && Auth::user()->isAdministrator())
                <div class="nav-item">
                    <a href="{{ route('admin.users.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Manajemen User</span>
                    </a>
                </div>
                @endif

                <div class="nav-item">
                    <a href="{{ route('admin.loa-templates.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.loa-templates.*') ? 'active' : '' }}">
                        <i class="fas fa-file-code"></i>
                        <span class="nav-text">Template LOA</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('loa.validated') }}" 
                       class="nav-link" target="_blank">
                        <i class="fas fa-search"></i>
                        <span class="nav-text">Cari LOA</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('home') }}" 
                       class="nav-link" target="_blank">
                        <i class="fas fa-globe"></i>
                        <span class="nav-text">Website</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="breadcrumb-container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="header-right">
                    <!-- Search -->
                    <div class="search-box">
                        <input type="text" placeholder="Cari..." class="form-control">
                        <i class="fas fa-search search-icon"></i>
                    </div>

                    <!-- Dark mode toggle -->
                    <button class="dark-mode-toggle" onclick="toggleDarkMode()">
                        <i class="fas fa-moon"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </button>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <div class="user-menu" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <p class="user-name">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="user-role">{{ Auth::user()->getRoleDisplayNameAttribute() ?? 'Administrator' }}</p>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.querySelector('.dark-mode-toggle i');
            if (document.body.classList.contains('dark-mode')) {
                icon.className = 'fas fa-sun';
                localStorage.setItem('darkMode', 'enabled');
            } else {
                icon.className = 'fas fa-moon';
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.querySelector('.dark-mode-toggle i').className = 'fas fa-sun';
        }

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });

            // Toggle sidebar on mobile
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('active');
                }
            });
        });

        // Auto-hide alerts
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

        // Loading states for buttons
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="loading-spinner"></span> Loading...';
            button.disabled = true;
            
            setTimeout(function() {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }

        // Add loading to all form submit buttons
        document.addEventListener('DOMContentLoaded', function() {
            const submitButtons = document.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    showLoading(this);
                });
            });
        });
    </script>

    @yield('scripts')

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @if(session('success'))
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Berhasil</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
        @endif
    </div>
</body>
</html>
