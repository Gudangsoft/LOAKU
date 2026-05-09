@extends('publisher.layout')

@section('title', 'LOA Templates')

@push('styles')
<style>
.starter-card {
    border: 2px solid #e9ecef;
    border-radius: 14px;
    transition: all .25s;
    cursor: pointer;
    background: #fff;
    position: relative;
    overflow: hidden;
}
.starter-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 6px 24px rgba(13,110,253,.15);
    transform: translateY(-3px);
}
.starter-card .card-accent {
    height: 5px;
    border-radius: 14px 14px 0 0;
}
.starter-card .lang-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    font-size: .7rem;
    padding: 3px 8px;
    border-radius: 20px;
}
.starter-card .preview-mini {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: .72rem;
    color: #6c757d;
    line-height: 1.6;
    min-height: 68px;
    font-family: 'Times New Roman', serif;
    border-left: 3px solid;
    margin: 10px 0;
}
.starter-card .icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-file-code me-2 text-primary"></i>LOA Templates</h4>
        <p class="text-muted mb-0 small">Kelola template surat penerimaan artikel (LOA)</p>
    </div>
    <a href="{{ route('publisher.loa-templates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Buat Template Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ===== POPULAR STARTER TEMPLATES ===== --}}
<div class="card mb-4 border-0" style="background:linear-gradient(135deg,#f0f4ff 0%,#faf5ff 100%);border-radius:16px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="icon-circle" style="background:#e7f3ff;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-magic text-primary"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Template Populer Siap Pakai</h5>
                <small class="text-muted">Pilih salah satu, edit nama &amp; langsung simpan — tidak perlu mulai dari nol</small>
            </div>
        </div>

        <div class="row g-3">

            {{-- Card 1 --}}
            <div class="col-md-6 col-xl-4">
                <div class="starter-card h-100 p-3" onclick="usePreset('formal_id')">
                    <div class="card-accent" style="background:linear-gradient(90deg,#1e3c72,#2a5298);position:absolute;top:0;left:0;right:0;"></div>
                    <div class="pt-2">
                        <span class="lang-badge bg-info text-white">Indonesia</span>
                        <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                            <div class="icon-circle" style="background:#dbeafe;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-scroll text-primary" style="font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:.88rem;">Surat Resmi Formal</div>
                                <div class="text-muted" style="font-size:.72rem;">Bahasa Indonesia · Formal</div>
                            </div>
                        </div>
                        <div class="preview-mini" style="border-left-color:#1e3c72;">
                            SURAT PENERIMAAN NASKAH<br>
                            Judul : [judul artikel]...<br>
                            Penulis : [nama penulis]<br>
                            Telah diterima dan akan dipublikasikan...
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border"><i class="fas fa-file-alt me-1"></i>PDF Ready</span>
                            <button class="btn btn-sm btn-primary px-3">Gunakan <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="col-md-6 col-xl-4">
                <div class="starter-card h-100 p-3" onclick="usePreset('academic_en')">
                    <div class="card-accent" style="background:linear-gradient(90deg,#198754,#20c997);position:absolute;top:0;left:0;right:0;"></div>
                    <div class="pt-2">
                        <span class="lang-badge bg-success text-white">English</span>
                        <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                            <div class="icon-circle" style="background:#d1fae5;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-graduation-cap text-success" style="font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:.88rem;">Academic English</div>
                                <div class="text-muted" style="font-size:.72rem;">English · International Style</div>
                            </div>
                        </div>
                        <div class="preview-mini" style="border-left-color:#198754;">
                            LETTER OF ACCEPTANCE<br>
                            Dear [Author Name],<br>
                            We are pleased to inform you that your manuscript "<em>[title]</em>" has been accepted...
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border"><i class="fas fa-globe me-1"></i>International</span>
                            <button class="btn btn-sm btn-success px-3">Gunakan <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="col-md-6 col-xl-4">
                <div class="starter-card h-100 p-3" onclick="usePreset('bilingual')">
                    <div class="card-accent" style="background:linear-gradient(90deg,#6f42c1,#d63384);position:absolute;top:0;left:0;right:0;"></div>
                    <div class="pt-2">
                        <span class="lang-badge text-white" style="background:#6f42c1;">Bilingual</span>
                        <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                            <div class="icon-circle" style="background:#ede9fe;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-language" style="font-size:.85rem;color:#6f42c1;"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:.88rem;">Bilingual ID + EN</div>
                                <div class="text-muted" style="font-size:.72rem;">Indonesian &amp; English · Dual</div>
                            </div>
                        </div>
                        <div class="preview-mini" style="border-left-color:#6f42c1;">
                            SURAT PENERIMAAN / LETTER OF ACCEPTANCE<br>
                            <strong>[ID]</strong> Telah diterima...<br>
                            <strong>[EN]</strong> Has been accepted...
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border"><i class="fas fa-flag me-1"></i>Dual Language</span>
                            <button class="btn btn-sm px-3 text-white" style="background:#6f42c1;">Gunakan <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4 --}}
            <div class="col-md-6 col-xl-4">
                <div class="starter-card h-100 p-3" onclick="usePreset('letterhead')">
                    <div class="card-accent" style="background:linear-gradient(90deg,#dc3545,#fd7e14);position:absolute;top:0;left:0;right:0;"></div>
                    <div class="pt-2">
                        <span class="lang-badge bg-warning text-dark">Indonesia</span>
                        <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                            <div class="icon-circle" style="background:#fee2e2;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-building" style="font-size:.85rem;color:#dc3545;"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:.88rem;">Kop Surat Institusi</div>
                                <div class="text-muted" style="font-size:.72rem;">Indonesia · Letterhead Style</div>
                            </div>
                        </div>
                        <div class="preview-mini" style="border-left-color:#dc3545;">
                            ▬▬▬ [Nama Jurnal] ▬▬▬<br>
                            Kepada Yth. [Penulis]<br>
                            Nomor: LOA-[kode]<br>
                            Perihal: Penerimaan Naskah
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border"><i class="fas fa-stamp me-1"></i>Letterhead</span>
                            <button class="btn btn-sm btn-danger px-3">Gunakan <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 5 --}}
            <div class="col-md-6 col-xl-4">
                <div class="starter-card h-100 p-3" onclick="usePreset('modern_en')">
                    <div class="card-accent" style="background:linear-gradient(90deg,#0dcaf0,#0d6efd);position:absolute;top:0;left:0;right:0;"></div>
                    <div class="pt-2">
                        <span class="lang-badge bg-info text-white">English</span>
                        <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                            <div class="icon-circle" style="background:#cff4fc;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-star" style="font-size:.85rem;color:#0dcaf0;"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:.88rem;">Modern Minimalist</div>
                                <div class="text-muted" style="font-size:.72rem;">English · Clean &amp; Contemporary</div>
                            </div>
                        </div>
                        <div class="preview-mini" style="border-left-color:#0dcaf0;">
                            ● Letter of Acceptance ●<br>
                            <em>"[Article Title]"</em><br>
                            ...has been accepted for publication in [Journal]...
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border"><i class="fas fa-paint-brush me-1"></i>Modern</span>
                            <button class="btn btn-sm btn-info px-3 text-white">Gunakan <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 6 --}}
            <div class="col-md-6 col-xl-4">
                <div class="starter-card h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center"
                     onclick="window.location='{{ route('publisher.loa-templates.create') }}'">
                    <div class="card-accent" style="background:#e9ecef;position:absolute;top:0;left:0;right:0;"></div>
                    <div style="font-size:2.2rem;color:#adb5bd;margin-bottom:8px;"><i class="fas fa-plus-circle"></i></div>
                    <div class="fw-bold text-muted">Mulai dari Kosong</div>
                    <div class="text-muted small">Buat template sepenuhnya kustom</div>
                    <button class="btn btn-sm btn-outline-secondary mt-3 px-3">Buat Baru</button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===== EXISTING TEMPLATES LIST ===== --}}
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Template Saya
            </h5>
            <span class="badge bg-info">{{ $templates->total() }} Template</span>
        </div>
    </div>
    <div class="card-body">
        @if($templates->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Template</th>
                            <th>Bahasa</th>
                            <th>Format</th>
                            <th>Sumber</th>
                            <th>Status</th>
                            <th>Default</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                            <tr>
                                <td>
                                    <strong>{{ $template->name }}</strong>
                                    @if($template->description)
                                        <br><small class="text-muted">{{ Str::limit($template->description, 55) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($template->language)
                                        @case('id')  <span class="badge bg-info">Indonesia</span> @break
                                        @case('en')  <span class="badge bg-success">English</span> @break
                                        @case('both')<span class="badge bg-primary">Bilingual</span> @break
                                    @endswitch
                                </td>
                                <td>
                                    <span class="badge {{ $template->format === 'pdf' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                        <i class="fas {{ $template->format === 'pdf' ? 'fa-file-pdf' : 'fa-code' }} me-1"></i>
                                        {{ strtoupper($template->format) }}
                                    </span>
                                </td>
                                <td>
                                    @if($template->publisher)
                                        <span class="text-primary small"><i class="fas fa-user me-1"></i>{{ Str::limit($template->publisher->name, 25) }}</span>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-cog me-1"></i>System</span>
                                    @endif
                                </td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-pause me-1"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($template->is_default)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Default</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('publisher.loa-templates.show', $template) }}"
                                           class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('publisher.loa-templates.preview', $template) }}"
                                           class="btn btn-sm btn-outline-secondary" title="Preview" target="_blank">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        @if($template->publisher_id)
                                            <a href="{{ route('publisher.loa-templates.edit', $template) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                    onclick="confirmDelete({{ $template->id }}, '{{ addslashes($template->name) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-outline-secondary disabled" title="System template — tidak bisa diedit">
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
            @if($templates->hasPages())
                <div class="d-flex justify-content-center mt-3">{{ $templates->links() }}</div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-code fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Template</h5>
                <p class="text-muted small">Pilih salah satu template populer di atas untuk memulai.</p>
            </div>
        @endif
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Template?',
        text: `Template "${name}" akan dihapus permanen.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.getElementById('deleteForm');
            f.action = `/publisher/loa-templates/${id}`;
            f.submit();
        }
    });
}

function usePreset(key) {
    window.location = '{{ route('publisher.loa-templates.create') }}?preset=' + key;
}
</script>
@endsection
