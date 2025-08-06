@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-file-code me-2"></i>LOA Templates</h2>
        <p class="text-muted mb-0">Kelola template surat penerimaan artikel (LOA)</p>
    </div>
    <a href="{{ route('publisher.loa-templates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Buat Template Baru
    </a>
</div>

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

<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Daftar Template LOA
            </h5>
            <span class="badge bg-info">{{ $templates->total() }} Total Template</span>
        </div>
    </div>
    <div class="card-body">
        @if($templates->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Template</th>
                            <th>Bahasa</th>
                            <th>Format</th>
                            <th>Publisher</th>
                            <th>Status</th>
                            <th>Default</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $template->name }}</strong>
                                        @if($template->description)
                                            <br><small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @switch($template->language)
                                        @case('id')
                                            <span class="badge bg-info">Indonesia</span>
                                            @break
                                        @case('en')
                                            <span class="badge bg-success">English</span>
                                            @break
                                        @case('both')
                                            <span class="badge bg-primary">Bilingual</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <span class="badge {{ $template->format === 'pdf' ? 'bg-danger' : 'bg-warning' }}">
                                        <i class="fas {{ $template->format === 'pdf' ? 'fa-file-pdf' : 'fa-code' }} me-1"></i>
                                        {{ strtoupper($template->format) }}
                                    </span>
                                </td>
                                <td>
                                    @if($template->publisher)
                                        <span class="text-primary">{{ $template->publisher->name }}</span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-cog me-1"></i>System Template
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-pause me-1"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($template->is_default)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star me-1"></i>Default
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('publisher.loa-templates.show', $template) }}" 
                                           class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('publisher.loa-templates.preview', $template) }}" 
                                           class="btn btn-sm btn-outline-secondary" title="Preview"
                                           target="_blank">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        @if($template->publisher_id)
                                            <a href="{{ route('publisher.loa-templates.edit', $template) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Hapus"
                                                    onclick="confirmDelete({{ $template->id }}, '{{ $template->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-outline-secondary disabled" title="System template">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($templates->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $templates->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-file-code fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">Belum Ada Template LOA</h5>
                <p class="text-muted">Template LOA yang Anda buat akan muncul di sini.</p>
                <a href="{{ route('publisher.loa-templates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Template Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(templateId, templateName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus template "${templateName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = `/publisher/loa-templates/${templateId}`;
            form.submit();
        }
    });
}
</script>
@endsection
