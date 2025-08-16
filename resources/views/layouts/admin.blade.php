<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - LOA Management System</title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.4.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 65px;
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        /* Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: #f8fafc;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--sidebar-bg);
            z-index: 999;
            overflow-y: auto;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            background: transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-hover);
            color: white;
            border-right: 3px solid var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); }

        .stat-content h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .stat-content p {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Tables */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: #374151;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: var(--border-color);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .header-brand {
                font-size: 1rem;
            }
        }

        /* Loading */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fecaca, #fca5a5);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="d-flex align-items-center">
            <button class="btn btn-link d-md-none me-3" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="header-brand">
                <i class="fas fa-certificate me-2"></i>LOA Management
            </a>
        </div>
        
        <div class="header-user">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="fw-semibold">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <small class="text-muted">{{ Auth::user()->role ?? 'Administrator' }}</small>
                </div>
            </div>
            
            <!-- Dropdown Menu -->
            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" 
                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                        style="border-color: rgba(255,255,255,0.3);">
                    <i class="fas fa-user-circle me-1"></i>
                    <span class="d-none d-sm-inline">Menu</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">{{ Auth::user()->name ?? 'Admin' }}</h6></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger" 
                                    onclick="return confirm('Yakin ingin logout?')">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            
            <!-- Direct Logout Button (Alternative) -->
            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm" 
                        onclick="return confirm('Yakin ingin logout?')"
                        title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline ms-1">Logout</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="admin-sidebar" id="sidebar">
        <div class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-section">
                <div class="nav-section-title">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Overview</span>
                </a>
            </div>

            <!-- LOA Management -->
            <div class="nav-section">
                <div class="nav-section-title">LOA Management</div>
                <a href="{{ route('admin.loa-requests.index') }}" class="nav-link {{ request()->routeIs('admin.loa-requests.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>LOA Requests</span>
                    @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>

            <!-- Data Master -->
            <div class="nav-section">
                <div class="nav-section-title">Data Master</div>
                <a href="{{ route('admin.publishers.index') }}" class="nav-link {{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Publishers</span>
                </a>
                <a href="{{ route('admin.journals.index') }}" class="nav-link {{ request()->routeIs('admin.journals.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Journals</span>
                </a>
                <a href="{{ route('admin.loa-templates.index') }}" class="nav-link {{ request()->routeIs('admin.loa-templates.*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i>
                    <span>LOA Templates</span>
                </a>
            </div>

            <!-- System Management (Super Admin Only) -->
            @if(Auth::user() && (Auth::user()->hasRole('super_admin') || Auth::user()->is_admin))
            <div class="nav-section">
                <div class="nav-section-title">System</div>
                <a href="{{ route('admin.publisher-validation.index') }}" class="nav-link {{ request()->routeIs('admin.publisher-validation.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Publisher Validation</span>
                    @php
                        $pendingCount = \App\Models\Publisher::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                <a href="{{ route('admin.website-settings.index') }}" class="nav-link {{ request()->routeIs('admin.website-settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    <span>Website Settings</span>
                </a>
                <a href="{{ route('admin.system-logs.index') }}" class="nav-link {{ request()->routeIs('admin.system-logs.*') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>System Logs</span>
                </a>
                <a href="{{ route('admin.supports.index') }}" class="nav-link {{ request()->routeIs('admin.supports.*') ? 'active' : '' }}">
                    <i class="fas fa-handshake"></i>
                    <span>Support Management</span>
                </a>
                <a href="{{ route('admin.backups.index') }}" class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                    <i class="fas fa-archive"></i>
                    <span>Backup Management</span>
                </a>
            </div>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            <p class="page-subtitle">@yield('subtitle', 'Manage your LOA system efficiently')</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
            
            // Initialize all Bootstrap dropdowns
            const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
            const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
            
            // Logout confirmation
            const logoutButtons = document.querySelectorAll('button[type="submit"]');
            logoutButtons.forEach(button => {
                const form = button.closest('form');
                if (form && form.action.includes('logout')) {
                    button.addEventListener('click', function(e) {
                        if (!confirm('Yakin ingin logout?')) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }
            });
        });

        // Add CSRF token to all AJAX requests
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
