@extends('admin.layouts.app')

@section('title', 'Kelola Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        Kelola Permissions: {{ $account->full_name }}
                        <span class="badge bg-{{ $account->role == 'administrator' ? 'danger' : 'info' }} ms-2">
                            {{ ucfirst($account->role) }}
                        </span>
                    </h5>
                    <div>
                        <a href="{{ route('admin.accounts.show', $account) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.accounts.permissions.update', $account) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Informasi Akun
                                </h6>
                                
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($account->avatar)
                                                <img src="{{ asset('storage/' . $account->avatar) }}" alt="Avatar" class="rounded-circle me-3" width="50" height="50">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $account->full_name }}</h6>
                                                <small class="text-muted">@{{ $account->username }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <strong>Email:</strong> {{ $account->email }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Role:</strong> 
                                            <span class="badge bg-{{ $account->role == 'administrator' ? 'danger' : 'info' }}">
                                                {{ ucfirst($account->role) }}
                                            </span>
                                        </div>
                                        @if($account->publisher)
                                            <div class="mb-2">
                                                <strong>Publisher:</strong> {{ $account->publisher->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-key me-1"></i>
                                    Kelola Permissions
                                </h6>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Catatan:</strong> Permissions ini memberikan akses khusus kepada pengguna untuk melakukan tindakan tertentu di sistem.
                                </div>

                                <!-- Permissions List -->
                                <div class="row">
                                    @foreach($allPermissions as $permission => $description)
                                        <div class="col-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission }}" 
                                                       id="permission_{{ $permission }}"
                                                       {{ in_array($permission, $account->permissions ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission }}">
                                                    <strong>{{ $description }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $permission }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Default Permissions Info -->
                                <div class="alert alert-secondary mt-3">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Default Permissions untuk {{ ucfirst($account->role) }}:
                                    </h6>
                                    <ul class="mb-0">
                                        @php
                                            $defaultPermissions = \App\Models\Account::getDefaultPermissions($account->role);
                                        @endphp
                                        @if(empty($defaultPermissions))
                                            <li class="text-muted">Tidak ada default permissions</li>
                                        @else
                                            @foreach($defaultPermissions as $defaultPerm)
                                                <li>{{ $allPermissions[$defaultPerm] ?? $defaultPerm }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-warning" onclick="resetToDefault()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset ke Default
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('admin.accounts.show', $account) }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Update Permissions
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetToDefault() {
    if (confirm('Yakin ingin mereset permissions ke default untuk role {{ $account->role }}?')) {
        // Uncheck all checkboxes first
        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Check default permissions
        const defaultPermissions = @json(\App\Models\Account::getDefaultPermissions($account->role));
        defaultPermissions.forEach(permission => {
            const checkbox = document.getElementById('permission_' + permission);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
}

// Select All / Deselect All functionality
function toggleAll() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

// Add toggle all button
document.addEventListener('DOMContentLoaded', function() {
    const permissionsSection = document.querySelector('.row');
    if (permissionsSection) {
        const toggleButton = document.createElement('div');
        toggleButton.className = 'col-12 mb-3';
        toggleButton.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAll()">
                <i class="fas fa-check-square me-1"></i>
                Toggle Semua
            </button>
        `;
        permissionsSection.insertBefore(toggleButton, permissionsSection.firstChild);
    }
});
</script>
@endsection
