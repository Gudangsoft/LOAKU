<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Publisher Dashboard') - {{ site_name() }}</title>
    @if(site_favicon())
        <link rel="icon" type="image/x-icon" href="{{ site_favicon() }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        .recent-requests-card {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4 px-2">
                        @if(site_logo())
                            <div style="background:rgba(255,255,255,.12);border-radius:10px;padding:.5rem .75rem;display:inline-block;margin-bottom:.4rem;">
                                <img src="{{ site_logo() }}" alt="{{ site_name() }}"
                                     style="max-height:36px; max-width:130px; object-fit:contain; display:block;">
                            </div>
                            <div class="text-white fw-semibold" style="font-size:.9rem;">{{ site_name() }}</div>
                            <div class="text-white-50 small">Publisher Panel</div>
                        @else
                            <h5 class="text-white mb-0">
                                <i class="fas fa-certificate me-2"></i>{{ site_name() }}
                            </h5>
                            <div class="text-white-50 small">Publisher Panel</div>
                        @endif
                        <hr class="bg-white opacity-25 mt-2">
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.dashboard') ? 'active' : '' }}" href="{{ route('publisher.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.publishers*') ? 'active' : '' }}" href="{{ route('publisher.publishers.index') }}">
                                <i class="fas fa-building me-2"></i>Publisher Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.journals*') ? 'active' : '' }}" href="{{ route('publisher.journals.index') }}">
                                <i class="fas fa-book me-2"></i>Journal Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.loa-requests*') ? 'active' : '' }}" href="{{ route('publisher.loa-requests.index') }}">
                                <i class="fas fa-file-alt me-2"></i>LOA Requests
                                @if(isset($stats['loa_requests']['pending']) && $stats['loa_requests']['pending'] > 0)
                                    <span class="badge bg-warning ms-2">{{ $stats['loa_requests']['pending'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.loa-templates*') ? 'active' : '' }}" href="{{ route('publisher.loa-templates.index') }}">
                                <i class="fas fa-file-code me-2"></i>LOA Templates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.subscription*') ? 'active' : '' }}" href="{{ route('publisher.subscription.index') }}">
                                <i class="fas fa-box-open me-2"></i>Paket Langganan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.domain*') ? 'active' : '' }}" href="{{ route('publisher.domain.index') }}">
                                <i class="fas fa-globe me-2"></i>Domain Kustom
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('publisher.profile*') ? 'active' : '' }}" href="{{ route('publisher.profile') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                        </li>
                        <hr class="bg-white">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                                <i class="fas fa-bars"></i>
                            </button>
                            <span class="fw-semibold text-muted d-none d-md-inline" style="font-size:.9rem;">@yield('title', 'Dashboard')</span>
                        </div>
                        
                        <div class="ms-auto d-flex align-items-center gap-3">

                            {{-- Bell Notification --}}
                            <div class="position-relative" id="notifWrapper">
                                <button class="btn btn-link p-0 text-secondary position-relative" id="notifBtn" title="Notifikasi">
                                    <i class="fas fa-bell fs-5"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                          id="notifBadge" style="display:none; font-size:.6rem; min-width:18px;">0</span>
                                </button>
                                <div id="notifDropdown" class="dropdown-menu dropdown-menu-end shadow show"
                                     style="display:none; position:absolute; right:0; top:110%; width:320px; z-index:9999; border-radius:8px;">
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                        <strong class="small">Notifikasi</strong>
                                        <button class="btn btn-link btn-sm p-0 text-primary small" onclick="markAllRead()">Tandai semua dibaca</button>
                                    </div>
                                    <div id="notifList" style="max-height:320px; overflow-y:auto;">
                                        <div class="text-center text-muted py-3 small" id="notifLoading">Memuat...</div>
                                    </div>
                                </div>
                            </div>

                            <div class="user-avatar me-2">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <small class="text-muted d-block">Welcome back,</small>
                                <strong>{{ Auth::user()->name }}</strong>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="px-3">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @yield('scripts')
    @stack('scripts')

<script>
const notifBtn      = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');
const notifBadge    = document.getElementById('notifBadge');
const notifList     = document.getElementById('notifList');
let notifOpen = false;

function loadNotifications() {
    fetch('{{ route("notifications.fetch") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            // Badge
            if (data.unread_count > 0) {
                notifBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                notifBadge.style.display = 'inline';
            } else {
                notifBadge.style.display = 'none';
            }
            // List
            if (data.notifications.length === 0) {
                notifList.innerHTML = '<div class="text-center text-muted py-3 small">Tidak ada notifikasi</div>';
                return;
            }
            notifList.innerHTML = data.notifications.map(n => `
                <a href="${n.url}" class="d-block text-decoration-none px-3 py-2 border-bottom notif-item ${n.read ? '' : 'bg-light'}"
                   onclick="markRead('${n.id}', event)" style="cursor:pointer;">
                    <div class="d-flex align-items-start gap-2">
                        <span class="mt-1" style="font-size:.75rem;">
                            ${n.type === 'loa_approved'
                                ? '<i class="fas fa-check-circle text-success"></i>'
                                : '<i class="fas fa-times-circle text-danger"></i>'}
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">${n.title}</div>
                            <div class="text-muted" style="font-size:.78rem;">${n.body}</div>
                            <div class="text-muted" style="font-size:.72rem;">${n.time}</div>
                        </div>
                        ${!n.read ? '<span class="bg-primary rounded-circle" style="width:8px;height:8px;flex-shrink:0;margin-top:4px;"></span>' : ''}
                    </div>
                </a>`).join('');
        });
}

function markRead(id, e) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => loadNotifications());
}

function markAllRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => loadNotifications());
}

notifBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    notifOpen = !notifOpen;
    notifDropdown.style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) loadNotifications();
});

document.addEventListener('click', function(e) {
    if (!document.getElementById('notifWrapper').contains(e.target)) {
        notifOpen = false;
        notifDropdown.style.display = 'none';
    }
});

// Auto-refresh badge setiap 60 detik
loadNotifications();
setInterval(loadNotifications, 60000);
</script>
</body>
</html>
