@extends('admin.layout')

@section('title', 'Manajemen Support/Sponsor')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-handshake me-2"></i>Manajemen Support/Sponsor
        </h1>
        <a href="{{ route('admin.supports.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
            Tambah Support
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Support/Sponsor</h6>
        </div>
        <div class="card-body">
            @if($supports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Logo</th>
                                <th width="20%">Nama</th>
                                <th width="20%">Website</th>
                                <th width="25%">Deskripsi</th>
                                <th width="5%">Urutan</th>
                                <th width="5%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supports as $support)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    @if($support->logo)
                                        <img src="{{ $support->logo_url }}" alt="{{ $support->name }}" 
                                             class="img-thumbnail" style="max-width: 80px; max-height: 60px;">
                                    @else
                                        <span class="text-muted">No Logo</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $support->name }}</strong>
                                </td>
                                <td>
                                    @if($support->website)
                                        <a href="{{ $support->website }}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            {{ Str::limit($support->website, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ Str::limit($support->description, 50) ?: '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-secondary">{{ $support->order }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.supports.toggle', $support) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $support->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fas fa-{{ $support->is_active ? 'check' : 'times' }}"></i>
                                            {{ $support->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.supports.show', $support) }}" class="btn btn-info btn-sm" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.supports.edit', $support) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                                onclick="deleteSupport({{ $support->id }}, '{{ $support->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $support->id }}" action="{{ route('admin.supports.destroy', $support) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada data support/sponsor</h5>
                    <p class="text-muted">Klik tombol "Tambah Support" untuk menambah data pertama.</p>
                    <a href="{{ route('admin.supports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Support
                    </a>
                </div>
            @endif
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

// DataTable initialization
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[ 5, "asc" ]], // Sort by order column
        "pageLength": 25
    });
});
</script>
@endpush
@endsection
