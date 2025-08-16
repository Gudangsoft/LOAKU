@extends('admin.layout')

@section('title', 'Edit Support/Sponsor')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Edit Support/Sponsor
        </h1>
        <a href="{{ route('admin.supports.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>
            Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Support</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supports.update', $support) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Nama Organisasi/Instansi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $support->name) }}" required
                                           placeholder="Contoh: Kementerian Pendidikan">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="order" class="form-label">Urutan Tampil <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $support->order) }}" required min="0"
                                           placeholder="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Semakin kecil nomor, semakin awal tampil</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="website" class="form-label">Website URL</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website', $support->website) }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      placeholder="Deskripsi singkat tentang organisasi/instansi">{{ old('description', $support->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            
                            @if($support->logo)
                                <div class="mb-2">
                                    <label class="form-label d-block">Logo Saat Ini:</label>
                                    <img src="{{ $support->logo_url }}" alt="{{ $support->name }}" 
                                         class="img-thumbnail mb-2" style="max-width: 200px;">
                                </div>
                            @endif
                            
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF, SVG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah logo.</small>
                            
                            <div id="logoPreview" class="mt-3" style="display: none;">
                                <label class="form-label d-block">Preview Logo Baru:</label>
                                <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $support->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif (tampil di website)
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Support
                            </button>
                            <a href="{{ route('admin.supports.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Logo akan ditampilkan di bagian footer website
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-sort-numeric-down text-info me-2"></i>
                            Urutan menentukan posisi tampil logo (kiri ke kanan)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-link text-success me-2"></i>
                            Website URL opsional, logo akan bisa diklik jika diisi
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-eye text-primary me-2"></i>
                            Centang "Aktif" agar logo tampil di website
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Logo preview
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('logoPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('logoPreview').style.display = 'none';
    }
});
</script>
@endpush
@endsection
