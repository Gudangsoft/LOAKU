@extends('admin.layouts.app')

@section('title', 'Edit Akun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Akun: {{ $account->full_name }}</h5>
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
                    <form method="POST" action="{{ route('admin.accounts.update', $account) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           id="username" name="username" value="{{ old('username', $account->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $account->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Confirmation -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>

                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name', $account->full_name) }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Current Avatar -->
                                @if($account->avatar)
                                    <div class="mb-3">
                                        <label class="form-label">Avatar Saat Ini</label>
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $account->avatar) }}" alt="Current Avatar" class="rounded" style="max-width: 100px;">
                                        </div>
                                    </div>
                                @endif

                                <!-- Phone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $account->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $value => $label)
                                            <option value="{{ $value }}" {{ old('role', $account->role) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Publisher (Only for Publisher Role) -->
                                <div class="mb-3" id="publisher-field" style="{{ $account->role == 'publisher' ? 'display: block;' : 'display: none;' }}">
                                    <label for="publisher_id" class="form-label">Publisher <span class="text-danger">*</span></label>
                                    <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                            id="publisher_id" name="publisher_id" {{ $account->role == 'publisher' ? 'required' : '' }}>
                                        <option value="">Pilih Publisher</option>
                                        @foreach($publishers as $publisher)
                                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $account->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('publisher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $account->status) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Avatar -->
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Avatar Baru</label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                           id="avatar" name="avatar" accept="image/*">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.accounts.permissions', $account) }}" class="btn btn-warning">
                                    <i class="fas fa-key me-1"></i>
                                    Kelola Permissions
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Akun
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
document.getElementById('role').addEventListener('change', function() {
    var publisherField = document.getElementById('publisher-field');
    var publisherSelect = document.getElementById('publisher_id');
    
    if (this.value === 'publisher') {
        publisherField.style.display = 'block';
        publisherSelect.required = true;
    } else {
        publisherField.style.display = 'none';
        publisherSelect.required = false;
        publisherSelect.value = '';
    }
});

// Check on page load
document.addEventListener('DOMContentLoaded', function() {
    var roleSelect = document.getElementById('role');
    if (roleSelect.value === 'publisher') {
        document.getElementById('publisher-field').style.display = 'block';
        document.getElementById('publisher_id').required = true;
    }
});
</script>
@endsection
