@extends('layouts.admin')

@section('title', 'LOA Templates')
@section('subtitle', 'Manage LOA document templates')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt me-2"></i>
                Template LOA
            </h1>
            <p class="mb-0 text-muted">Kelola template untuk surat persetujuan naskah (LOA)</p>
        </div>
        <a href="{{ route('admin.loa-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Tambah Template
        </a>
    </div>

    <!-- Templates Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>
                Daftar Template LOA ({{ $templates->total() }} template)
            </h6>
        </div>
        <div class="card-body">
            @if($templates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Template</th>
                                <th>Bahasa</th>
                                <th>Format</th>
                                <th>Publisher</th>
                                <th>Status</th>
                                <th>Default</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $index => $template)
                                <tr class="{{ $template->is_default ? 'table-warning' : '' }}">
                                    <td>{{ $templates->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $template->name }}</strong>
                                        @if($template->description)
                                            <br><small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->language == 'id')
                                            <span class="badge bg-success">
                                                <i class="fas fa-flag me-1"></i>Indonesia
                                            </span>
                                        @elseif($template->language == 'en')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-flag-usa me-1"></i>English
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="fas fa-globe me-1"></i>Keduanya
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->format == 'html')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-code me-1"></i>HTML
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-file-pdf me-1"></i>PDF
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->publisher)
                                            <span class="text-primary">{{ $template->publisher->name }}</span>
                                        @else
                                            <span class="text-muted">Global</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->is_active)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause me-1"></i>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->is_default)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star me-1"></i>Default
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $template->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.loa-templates.show', $template) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.loa-templates.preview', $template) }}" 
                                               class="btn btn-success btn-sm" 
                                               title="Preview"
                                               target="_blank">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            <a href="{{ route('admin.loa-templates.edit', $template) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$template->is_default)
                                                <form action="{{ route('admin.loa-templates.destroy', $template) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm" 
                                                            title="Hapus"
                                                            onclick="return confirm('Yakin ingin menghapus template ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $templates->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada template LOA</h5>
                    <p class="text-muted">Klik tombol "Tambah Template" untuk membuat template baru</p>
                    <a href="{{ route('admin.loa-templates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Template Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        border-radius: 0.25rem;
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush
