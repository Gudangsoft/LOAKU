@extends('admin.layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelola Akun</h5>
                    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Akun
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select name="role" class="form-select">
                                        <option value="">Semua Role</option>
                                        <option value="administrator" {{ request('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                                        <option value="publisher" {{ request('role') == 'publisher' ? 'selected' : '' }}>Publisher</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari username, email, nama..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Accounts Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Nama Lengkap</th>
                                    <th>Role</th>
                                    <th>Publisher</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                <tr>
                                    <td>
                                        @if($account->avatar)
                                            <img src="{{ asset('storage/' . $account->avatar) }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $account->username }}</td>
                                    <td>{{ $account->email }}</td>
                                    <td>{{ $account->full_name }}</td>
                                    <td>
                                        @if($account->role == 'administrator')
                                            <span class="badge bg-danger">Administrator</span>
                                        @elseif($account->role == 'publisher')
                                            <span class="badge bg-info">Publisher</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($account->publisher)
                                            {{ $account->publisher->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($account->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($account->status == 'inactive')
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @else
                                            <span class="badge bg-warning">Suspended</span>
                                        @endif
                                    </td>
                                    <td>{{ $account->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.accounts.show', $account) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.accounts.permissions', $account) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-key"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Yakin ingin menghapus akun ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada akun yang terdaftar</p>
                                            <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary mt-2">
                                                Tambah Akun Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($accounts->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $accounts->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
