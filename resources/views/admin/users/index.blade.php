@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('subtitle', 'Kelola user dan pengaturan role')

@push('styles')
<style>
    .stat-card {
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid transparent;
        transition: box-shadow .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .stat-val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .stat-lbl { font-size: .75rem; color: #6B7280; margin-top: 2px; }

    .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .filter-tab {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid #E5E7EB;
        color: #6B7280;
        background: white;
        transition: all .15s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .filter-tab:hover { border-color: #6366F1; color: #6366F1; }
    .filter-tab.active { background: #6366F1; border-color: #6366F1; color: white; }
    .filter-tab .cnt {
        background: rgba(255,255,255,.25);
        border-radius: 10px;
        padding: 1px 6px;
        font-size: .7rem;
    }
    .filter-tab:not(.active) .cnt { background: #F3F4F6; color: #374151; }

    .user-avatar {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .875rem; font-weight: 700;
        color: white;
        flex-shrink: 0;
    }
    .avatar-admin     { background: linear-gradient(135deg, #EF4444, #DC2626); }
    .avatar-publisher { background: linear-gradient(135deg, #F59E0B, #D97706); }
    .avatar-member    { background: linear-gradient(135deg, #6366F1, #4F46E5); }
    .avatar-default   { background: linear-gradient(135deg, #6B7280, #4B5563); }

    .role-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: .75rem; font-weight: 600;
    }
    .role-admin     { background: #FEE2E2; color: #DC2626; }
    .role-publisher { background: #FEF3C7; color: #D97706; }
    .role-member    { background: #EEF2FF; color: #4F46E5; }

    .verified-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .72rem; font-weight: 600;
        padding: 2px 8px; border-radius: 20px;
    }
    .verified-yes { background: #DCFCE7; color: #16A34A; }
    .verified-no  { background: #F3F4F6; color: #6B7280; }

    .action-btn {
        width: 30px; height: 30px;
        border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .75rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .15s;
    }
    .action-btn:hover { transform: translateY(-1px); }
    .action-btn.view    { background: #EEF2FF; color: #4F46E5; }
    .action-btn.edit    { background: #FEF3C7; color: #D97706; }
    .action-btn.toggle  { background: #ECFDF5; color: #059669; }
    .action-btn.del     { background: #FEE2E2; color: #DC2626; }

    .search-input-wrap { position: relative; }
    .search-input-wrap .si {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%); color: #9CA3AF; font-size: .85rem;
        pointer-events: none;
    }
    .search-input-wrap input {
        padding-left: 34px;
        border-radius: 10px;
        border: 1.5px solid #E5E7EB;
        font-size: .875rem;
        height: 36px;
        transition: border-color .2s;
    }
    .search-input-wrap input:focus {
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        outline: none;
    }

    .empty-state { text-align: center; padding: 60px 0; color: #6B7280; }
    .empty-state i { font-size: 3rem; color: #D1D5DB; margin-bottom: 12px; display: block; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1 fw-bold" style="color:#111827">
                <i class="fas fa-users me-2" style="color:#6366F1"></i>Manajemen User
            </h1>
            <p class="mb-0 text-muted" style="font-size:.875rem">Kelola user dan pengaturan role dalam sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm px-3" style="border-radius:10px;font-weight:600">
            <i class="fas fa-plus me-1"></i> Tambah User
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#F5F3FF;border-color:#DDD6FE">
                <div class="stat-icon" style="background:#EDE9FE;color:#7C3AED"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-val" style="color:#6D28D9">{{ $stats['total'] }}</div>
                    <div class="stat-lbl">Total User</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#FFF1F2;border-color:#FECDD3">
                <div class="stat-icon" style="background:#FEE2E2;color:#DC2626"><i class="fas fa-user-shield"></i></div>
                <div>
                    <div class="stat-val" style="color:#B91C1C">{{ $stats['admin'] }}</div>
                    <div class="stat-lbl">Administrator</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#FFFBEB;border-color:#FDE68A">
                <div class="stat-icon" style="background:#FEF3C7;color:#D97706"><i class="fas fa-newspaper"></i></div>
                <div>
                    <div class="stat-val" style="color:#B45309">{{ $stats['publisher'] }}</div>
                    <div class="stat-lbl">Publisher</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#EEF2FF;border-color:#C7D2FE">
                <div class="stat-icon" style="background:#E0E7FF;color:#4F46E5"><i class="fas fa-user"></i></div>
                <div>
                    <div class="stat-val" style="color:#3730A3">{{ $stats['member'] }}</div>
                    <div class="stat-lbl">Member</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card" style="border-radius:16px;border:1px solid #E5E7EB;box-shadow:0 2px 12px rgba(0,0,0,.05)">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-4"
             style="background:white;border-bottom:1px solid #F3F4F6;border-radius:16px 16px 0 0">

            {{-- Filter Tabs --}}
            <div class="filter-tabs">
                <a href="{{ route('admin.users.index', array_merge(request()->except('role','page'), [])) }}"
                   class="filter-tab {{ !request('role') ? 'active' : '' }}">
                    Semua <span class="cnt">{{ $stats['total'] }}</span>
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role','page'), ['role'=>'admin'])) }}"
                   class="filter-tab {{ request('role')=='admin' ? 'active' : '' }}">
                    <span style="width:6px;height:6px;border-radius:50%;background:#DC2626;display:inline-block"></span>
                    Admin <span class="cnt">{{ $stats['admin'] }}</span>
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role','page'), ['role'=>'publisher'])) }}"
                   class="filter-tab {{ request('role')=='publisher' ? 'active' : '' }}">
                    <span style="width:6px;height:6px;border-radius:50%;background:#D97706;display:inline-block"></span>
                    Publisher <span class="cnt">{{ $stats['publisher'] }}</span>
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role','page'), ['role'=>'member'])) }}"
                   class="filter-tab {{ request('role')=='member' ? 'active' : '' }}">
                    <span style="width:6px;height:6px;border-radius:50%;background:#4F46E5;display:inline-block"></span>
                    Member <span class="cnt">{{ $stats['member'] }}</span>
                </a>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 align-items-center">
                @if(request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
                <div class="search-input-wrap">
                    <i class="fas fa-search si"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..." style="width:230px">
                </div>
                @if(request('q') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;height:36px;display:flex;align-items:center" title="Reset">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.875rem">
                    <thead style="background:#F9FAFB;border-bottom:1px solid #E5E7EB">
                        <tr>
                            <th class="ps-4" style="width:44px;color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">#</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">USER</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">EMAIL</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">ROLE</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">VERIFIKASI</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">TERDAFTAR</th>
                            <th class="pe-4 text-end" style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr style="border-bottom:1px solid #F3F4F6">
                            <td class="ps-4" style="vertical-align:middle;color:#9CA3AF;font-size:.8rem;padding:14px 8px">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar avatar-{{ $user->role ?? 'default' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:#111827">{{ $user->name }}</div>
                                        @if($user->id === auth()->id())
                                        <span style="font-size:.7rem;background:#EEF2FF;color:#4F46E5;padding:1px 6px;border-radius:10px;font-weight:600">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px;color:#374151;font-size:.83rem">
                                {{ $user->email }}
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                @php
                                    $roleClass = match($user->role) {
                                        'admin'     => 'role-admin',
                                        'publisher' => 'role-publisher',
                                        'member'    => 'role-member',
                                        default     => 'role-member',
                                    };
                                    $roleIcon = match($user->role) {
                                        'admin'     => 'fa-user-shield',
                                        'publisher' => 'fa-newspaper',
                                        default     => 'fa-user',
                                    };
                                @endphp
                                <span class="role-badge {{ $roleClass }}">
                                    <i class="fas {{ $roleIcon }}"></i>
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                @if($user->email_verified_at)
                                    <span class="verified-badge verified-yes">
                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="verified-badge verified-no">
                                        <i class="fas fa-clock"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                <div style="color:#374151;font-size:.83rem">{{ $user->created_at->format('d M Y') }}</div>
                                <div style="color:#9CA3AF;font-size:.75rem">{{ $user->created_at->format('H:i') }}</div>
                            </td>
                            <td class="pe-4 text-end" style="vertical-align:middle;padding:14px 8px">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.users.show', $user) }}" class="action-btn view" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button type="button"
                                            class="action-btn toggle"
                                            onclick="toggleRole({{ $user->id }})"
                                            title="Ubah Role ({{ $user->getRoleDisplayName() }} → berikutnya)">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <button type="button"
                                            class="action-btn del"
                                            onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-top:1px solid #F3F4F6">
                <div class="pagination-info">
                    Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
                </div>
                {{ $users->links() }}
            </div>

            @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h6 style="color:#374151">
                    @if(request('q') || request('role')) Tidak ada user yang cocok @else Belum ada user @endif
                </h6>
                <p style="font-size:.875rem">
                    @if(request('q') || request('role'))
                        <a href="{{ route('admin.users.index') }}">Reset filter</a>
                    @else
                        Tambah user pertama untuk memulai.
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDeleteUser(userId, userName) {
    if (typeof Swal === 'undefined') {
        if (confirm(`Hapus user "${userName}"? Tindakan tidak dapat dibatalkan.`)) {
            submitDeleteForm(userId);
        }
        return;
    }
    Swal.fire({
        title: 'Hapus User?',
        html: `Yakin ingin menghapus <strong>"${userName}"</strong>?<br><small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-secondary' },
        buttonsStyling: false
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
            submitDeleteForm(userId);
        }
    });
}

function submitDeleteForm(userId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/users/${userId}`;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}

function toggleRole(userId) {
    if (typeof Swal === 'undefined') {
        if (!confirm('Ubah role user ini?')) return;
        performToggleRole(userId);
        return;
    }
    Swal.fire({
        title: 'Ubah Role?',
        text: 'Role akan diubah ke role berikutnya (member → publisher → admin → member).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366F1',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-secondary' },
        buttonsStyling: false
    }).then(result => { if (result.isConfirmed) performToggleRole(userId); });
}

function performToggleRole(userId) {
    fetch(`/admin/users/${userId}/toggle-role`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                alert(data.message); location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') Swal.fire('Error', data.error, 'error');
            else alert(data.error);
        }
    })
    .catch(() => {
        if (typeof Swal !== 'undefined') Swal.fire('Error', 'Terjadi kesalahan.', 'error');
        else alert('Terjadi kesalahan.');
    });
}
</script>
@endpush
