@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-file-code me-2"></i>Detail Template LOA</h2>
        <p class="text-muted mb-0">{{ $loaTemplate->name }}</p>
    </div>
    <div>
        <a href="{{ route('publisher.loa-templates.preview', $loaTemplate) }}" 
           class="btn btn-info me-2" target="_blank">
            <i class="fas fa-search me-2"></i>Preview
        </a>
        @if($loaTemplate->publisher_id)
            <a href="{{ route('publisher.loa-templates.edit', $loaTemplate) }}" 
               class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
        @endif
        <a href="{{ route('publisher.loa-templates') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Template
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nama Template:</strong>
                        <p>{{ $loaTemplate->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Publisher:</strong>
                        <p>
                            @if($loaTemplate->publisher)
                                <span class="text-primary">{{ $loaTemplate->publisher->name }}</span>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-cog me-1"></i>System Template
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($loaTemplate->description)
                    <div class="mb-3">
                        <strong>Deskripsi:</strong>
                        <p>{{ $loaTemplate->description }}</p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <strong>Bahasa:</strong>
                        <p>
                            @switch($loaTemplate->language)
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
                        </p>
                    </div>
                    <div class="col-md-3">
                        <strong>Format:</strong>
                        <p>
                            <span class="badge {{ $loaTemplate->format === 'pdf' ? 'bg-danger' : 'bg-warning' }}">
                                <i class="fas {{ $loaTemplate->format === 'pdf' ? 'fa-file-pdf' : 'fa-code' }} me-1"></i>
                                {{ strtoupper($loaTemplate->format) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <strong>Status:</strong>
                        <p>
                            @if($loaTemplate->is_active)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-pause me-1"></i>Tidak Aktif
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <strong>Default:</strong>
                        <p>
                            @if($loaTemplate->is_default)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Default
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-code me-2"></i>Template Content
                </h5>
            </div>
            <div class="card-body">
                @if($loaTemplate->header_template)
                    <div class="mb-4">
                        <h6><i class="fas fa-header me-2"></i>Header Template</h6>
                        <pre class="bg-light p-3 rounded"><code>{{ $loaTemplate->header_template }}</code></pre>
                    </div>
                @endif

                <div class="mb-4">
                    <h6><i class="fas fa-align-left me-2"></i>Body Template</h6>
                    <pre class="bg-light p-3 rounded"><code>{{ $loaTemplate->body_template }}</code></pre>
                </div>

                @if($loaTemplate->footer_template)
                    <div class="mb-4">
                        <h6><i class="fas fa-grip-lines me-2"></i>Footer Template</h6>
                        <pre class="bg-light p-3 rounded"><code>{{ $loaTemplate->footer_template }}</code></pre>
                    </div>
                @endif

                @if($loaTemplate->css_styles)
                    <div class="mb-4">
                        <h6><i class="fab fa-css3-alt me-2"></i>CSS Styles</h6>
                        <pre class="bg-light p-3 rounded"><code>{{ $loaTemplate->css_styles }}</code></pre>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($loaTemplate->variables)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-code me-2"></i>Custom Variables
                    </h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded"><code>{{ json_encode($loaTemplate->variables, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
        @endif

        <div class="card{{ $loaTemplate->variables ? ' mt-4' : '' }}">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>System Variables
                </h5>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>Variabel yang tersedia:</strong><br>
                    • <code>{{article_title}}</code> - Judul artikel<br>
                    • <code>{{author_name}}</code> - Nama penulis<br>
                    • <code>{{registration_number}}</code> - Nomor registrasi<br>
                    • <code>{{publisher_name}}</code> - Nama publisher<br>
                    • <code>{{journal_name}}</code> - Nama jurnal<br>
                    • <code>{{submission_date}}</code> - Tanggal submit<br>
                    • <code>{{approval_date}}</code> - Tanggal approve<br>
                    • <code>{{website}}</code> - Website<br>
                    • <code>{{email}}</code> - Email
                </small>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Metadata
                </h5>
            </div>
            <div class="card-body">
                <small>
                    <strong>Dibuat:</strong> {{ $loaTemplate->created_at->format('d F Y H:i') }}<br>
                    <strong>Diperbarui:</strong> {{ $loaTemplate->updated_at->format('d F Y H:i') }}
                </small>
            </div>
        </div>

        @if($loaTemplate->publisher_id)
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('publisher.loa-templates.edit', $loaTemplate) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Template
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $loaTemplate->id }}, '{{ $loaTemplate->name }}')">
                            <i class="fas fa-trash me-2"></i>Hapus Template
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-4">
                <div class="card-body text-center">
                    <i class="fas fa-lock fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">System template tidak dapat diubah</p>
                </div>
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
