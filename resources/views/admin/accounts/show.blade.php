@extends('admin.layouts.app')

@section('title', 'Detail Akun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Akun: {{ $account->full_name }}</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.accounts.permissions', $account) }}" class="btn btn-info">
                            <i class="fas fa-key"></i> Permissions
                        </a>
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Avatar -->
                            <div class="text-center mb-4">
                                @if($account->avatar)
                                    <img src="{{ asset('storage/' . $account->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                        <i class="fas fa-user fa-4x text-white"></i>
                                    </div>
                                @endif
                                <h4 class="mt-3 mb-1">{{ $account->full_name }}</h4>
                                <p class="text-muted">@{{ $account->username }}</p>
                                
                                <!-- Status Badge -->
                                @if($account->status == 'active')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                @elseif($account->status == 'inactive')
                                    <span class="badge bg-secondary fs-6 px-3 py-2">
                                        <i class="fas fa-pause-circle me-1"></i>Tidak Aktif
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6 px-3 py-2">
                                        <i class="fas fa-ban me-1"></i>Suspended
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8">
                            <!-- Account Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Informasi Akun</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Username:</label>
                                        <p class="mb-0">{{ $account->username }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email:</label>
                                        <p class="mb-0">
                                            <a href="mailto:{{ $account->email }}">{{ $account->email }}</a>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Lengkap:</label>
                                        <p class="mb-0">{{ $account->full_name }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">No. Telepon:</label>
                                        <p class="mb-0">
                                            @if($account->phone)
                                                <a href="tel:{{ $account->phone }}">{{ $account->phone }}</a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Role & Publisher</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Role:</label>
                                        <p class="mb-0">
                                            @if($account->role == 'administrator')
                                                <span class="badge bg-danger fs-6">
                                                    <i class="fas fa-crown me-1"></i>Administrator
                                                </span>
                                            @elseif($account->role == 'publisher')
                                                <span class="badge bg-info fs-6">
                                                    <i class="fas fa-building me-1"></i>Publisher
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Publisher:</label>
                                        <p class="mb-0">
                                            @if($account->publisher)
                                                <a href="{{ route('admin.publishers.show', $account->publisher) }}" class="text-decoration-none">
                                                    {{ $account->publisher->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status:</label>
                                        <p class="mb-0">
                                            @if($account->status == 'active')
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                                </span>
                                            @elseif($account->status == 'inactive')
                                                <span class="text-secondary">
                                                    <i class="fas fa-pause-circle me-1"></i>Tidak Aktif
                                                </span>
                                            @else
                                                <span class="text-warning">
                                                    <i class="fas fa-ban me-1"></i>Suspended
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dibuat:</label>
                                        <p class="mb-0">{{ $account->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Terakhir Update:</label>
                                        <p class="mb-0">{{ $account->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissions Section -->
                            <hr>
                            <h6 class="text-muted mb-3">Permissions</h6>
                            @if($account->permissions && count($account->permissions) > 0)
                                <div class="row">
                                    @foreach($account->permissions as $permission)
                                        <div class="col-md-6 mb-2">
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>{{ ucwords(str_replace('_', ' ', $permission)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Tidak ada permissions khusus.</p>
                            @endif

                            <!-- Action Buttons -->
                            <hr>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.accounts.permissions', $account) }}" class="btn btn-warning">
                                    <i class="fas fa-key me-1"></i>
                                    Kelola Permissions
                                </a>
                                <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit Akun
                                </a>
                                <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus akun {{ $account->full_name }}? Tindakan ini tidak dapat dibatalkan.')">
                                        <i class="fas fa-trash me-1"></i>
                                        Hapus Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
