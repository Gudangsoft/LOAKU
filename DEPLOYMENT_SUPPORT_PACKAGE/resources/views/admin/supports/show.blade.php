@extends('admin.layout')

@section('title', 'Detail Support/Sponsor')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye me-2"></i>Detail Support/Sponsor
        </h1>
        <div>
            <a href="{{ route('admin.supports.edit', $support) }}" class="btn btn-warning btn-sm shadow-sm me-2">
                <i class="fas fa-edit fa-sm text-white-50 me-1"></i>
                Edit
            </a>
            <a href="{{ route('admin.supports.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Support</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Nama:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $support->name }}
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Website:</strong>
                        </div>
                        <div class="col-md-9">
                            @if($support->website)
                                <a href="{{ $support->website }}" target="_blank" class="text-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    {{ $support->website }}
                                </a>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Deskripsi:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $support->description ?: 'Tidak ada deskripsi' }}
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Urutan Tampil:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge badge-secondary">{{ $support->order }}</span>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge {{ $support->is_active ? 'badge-success' : 'badge-secondary' }}">
                                <i class="fas fa-{{ $support->is_active ? 'check' : 'times' }} me-1"></i>
                                {{ $support->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Dibuat:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $support->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Terakhir Update:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $support->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Logo</h6>
                </div>
                <div class="card-body text-center">
                    @if($support->logo)
                        <img src="{{ $support->logo_url }}" alt="{{ $support->name }}" 
                             class="img-fluid img-thumbnail mb-3" style="max-width: 100%;">
                        <br>
                        <small class="text-muted">{{ $support->logo }}</small>
                    @else
                        <div class="py-5">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Tidak ada logo</h6>
                            <a href="{{ route('admin.supports.edit', $support) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload me-1"></i>Upload Logo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supports.toggle', $support) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $support->is_active ? 'btn-warning' : 'btn-success' }} btn-sm w-100">
                            <i class="fas fa-{{ $support->is_active ? 'eye-slash' : 'eye' }} me-1"></i>
                            {{ $support->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger btn-sm w-100" 
                            onclick="deleteSupport({{ $support->id }}, '{{ $support->name }}')">
                        <i class="fas fa-trash me-1"></i>Hapus Support
                    </button>
                    
                    <form id="delete-form-{{ $support->id }}" action="{{ route('admin.supports.destroy', $support) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteSupport(id, name) {
    Swal.fire({
        title: 'Hapus Support?',
        text: `Yakin ingin menghapus support "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection
