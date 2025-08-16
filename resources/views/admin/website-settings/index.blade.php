@extends('layouts.admin')

@section('title', 'Website Settings')
@section('subtitle', 'Manage website configuration, logo, and branding settings')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Settings Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-globe me-2"></i>Pengaturan Umum Website
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.website-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Site Name -->
                        <div class="mb-3">
                            <label for="site_name" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nama Website <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('site_name') is-invalid @enderror" 
                                   id="site_name" 
                                   name="site_name" 
                                   value="{{ old('site_name', setting('site_name', 'LOA Management System')) }}"
                                   required>
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Site Description -->
                        <div class="mb-3">
                            <label for="site_description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Deskripsi Website
                            </label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                      id="site_description" 
                                      name="site_description" 
                                      rows="3"
                                      placeholder="Deskripsi singkat tentang website">{{ old('site_description', setting('site_description', '')) }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Admin Email -->
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Admin
                            </label>
                            <input type="email" 
                                   class="form-control @error('admin_email') is-invalid @enderror" 
                                   id="admin_email" 
                                   name="admin_email" 
                                   value="{{ old('admin_email', setting('admin_email', '')) }}"
                                   placeholder="admin@example.com">
                            @error('admin_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>Nomor Telepon
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', setting('phone', '')) }}"
                                   placeholder="+62 xxx xxxx xxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Alamat
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Alamat lengkap organisasi">{{ old('address', setting('address', '')) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Logo Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>Logo Website
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if(site_logo())
                        <div class="mb-3">
                            <img src="{{ site_logo() }}" 
                                 alt="Logo" 
                                 class="img-fluid"
                                 style="max-height: 120px;">
                        </div>
                        <button type="button" 
                                class="btn btn-danger btn-sm mb-3" 
                                onclick="deleteImage('logo')">
                            <i class="fas fa-trash me-1"></i>Hapus Logo
                        </button>
                    @else
                        <div class="mb-3">
                            <div class="bg-light p-4 rounded">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada logo</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.website-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields for other settings -->
                        <input type="hidden" name="site_name" value="{{ setting('site_name', 'LOA Management System') }}">
                        <input type="hidden" name="site_description" value="{{ setting('site_description', '') }}">
                        <input type="hidden" name="admin_email" value="{{ setting('admin_email', '') }}">
                        <input type="hidden" name="phone" value="{{ setting('phone', '') }}">
                        <input type="hidden" name="address" value="{{ setting('address', '') }}">

                        <div class="mb-3">
                            <input type="file" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" 
                                   name="logo" 
                                   accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: PNG, JPG, JPEG, SVG. Max: 2MB</div>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Logo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Favicon Settings -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star me-2"></i>Favicon
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if(site_favicon())
                        <div class="mb-3">
                            <img src="{{ site_favicon() }}" 
                                 alt="Favicon" 
                                 class="img-fluid"
                                 style="max-height: 64px;">
                        </div>
                        <button type="button" 
                                class="btn btn-danger btn-sm mb-3" 
                                onclick="deleteImage('favicon')">
                            <i class="fas fa-trash me-1"></i>Hapus Favicon
                        </button>
                    @else
                        <div class="mb-3">
                            <div class="bg-light p-4 rounded">
                                <i class="fas fa-star fa-2x text-muted"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada favicon</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.website-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields for other settings -->
                        <input type="hidden" name="site_name" value="{{ setting('site_name', 'LOA Management System') }}">
                        <input type="hidden" name="site_description" value="{{ setting('site_description', '') }}">
                        <input type="hidden" name="admin_email" value="{{ setting('admin_email', '') }}">
                        <input type="hidden" name="phone" value="{{ setting('phone', '') }}">
                        <input type="hidden" name="address" value="{{ setting('address', '') }}">

                        <div class="mb-3">
                            <input type="file" 
                                   class="form-control @error('favicon') is-invalid @enderror" 
                                   id="favicon" 
                                   name="favicon" 
                                   accept="image/*">
                            @error('favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: PNG, ICO, JPG, JPEG. Max: 1MB</div>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Favicon
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteImage(type) {
    if (confirm('Apakah Anda yakin ingin menghapus ' + type + '?')) {
        fetch('{{ route("admin.website-settings.delete-image") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus ' + type + ': ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus ' + type);
        });
    }
}
</script>

    </div>
</div>
@endsection
