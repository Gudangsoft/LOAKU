@extends('layouts.app')

@section('title', 'Tambah Template LOA - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>
                Tambah Template LOA
            </h1>
            <p class="mb-0 text-muted">Buat template baru untuk surat persetujuan naskah</p>
        </div>
        <a href="{{ route('admin.loa-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.loa-templates.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Dasar
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Template *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Template Default Indonesia"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Deskripsi singkat template ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="language" class="form-label fw-bold">Bahasa *</label>
                            <select class="form-select @error('language') is-invalid @enderror" 
                                    id="language" 
                                    name="language" 
                                    required>
                                <option value="id" {{ old('language') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="both" {{ old('language', 'both') == 'both' ? 'selected' : '' }}>Keduanya</option>
                            </select>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label fw-bold">Format *</label>
                            <select class="form-select @error('format') is-invalid @enderror" 
                                    id="format" 
                                    name="format" 
                                    required>
                                <option value="html" {{ old('format', 'html') == 'html' ? 'selected' : '' }}>HTML</option>
                                <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            </select>
                            @error('format')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="publisher_id" class="form-label fw-bold">Publisher</label>
                            <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                    id="publisher_id" 
                                    name="publisher_id">
                                <option value="">Global (Semua Publisher)</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('publisher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Kosongkan untuk template global</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    Template Aktif
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_default" 
                                       name="is_default" 
                                       value="1"
                                       {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_default">
                                    Jadikan Template Default
                                </label>
                            </div>
                            <small class="form-text text-muted">Template default akan digunakan secara otomatis</small>
                        </div>
                    </div>
                </div>

                <!-- Available Variables -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-tags me-2"></i>
                            Variabel Tersedia
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="variable-list" style="max-height: 400px; overflow-y: auto;">
                            <div class="row small">
                                <div class="col-12 mb-2">
                                    <strong class="text-primary">Publisher:</strong>
                                    <div class="ms-2">
                                        <code>{{publisher_name}}</code> - Nama Penerbit<br>
                                        <code>{{publisher_address}}</code> - Alamat<br>
                                        <code>{{publisher_email}}</code> - Email<br>
                                        <code>{{publisher_phone}}</code> - Telepon
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-success">Jurnal:</strong>
                                    <div class="ms-2">
                                        <code>{{journal_name}}</code> - Nama Jurnal<br>
                                        <code>{{journal_issn_e}}</code> - E-ISSN<br>
                                        <code>{{journal_issn_p}}</code> - P-ISSN<br>
                                        <code>{{chief_editor}}</code> - Pemimpin Redaksi
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-warning">LOA:</strong>
                                    <div class="ms-2">
                                        <code>{{loa_code}}</code> - Kode LOA<br>
                                        <code>{{article_title}}</code> - Judul Artikel<br>
                                        <code>{{author_name}}</code> - Nama Penulis<br>
                                        <code>{{author_email}}</code> - Email Penulis
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-secondary">Publikasi:</strong>
                                    <div class="ms-2">
                                        <code>{{volume}}</code> - Volume<br>
                                        <code>{{number}}</code> - Nomor<br>
                                        <code>{{month}}</code> - Bulan<br>
                                        <code>{{year}}</code> - Tahun<br>
                                        <code>{{registration_number}}</code> - No. Reg
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-danger">Tanggal:</strong>
                                    <div class="ms-2">
                                        <code>{{approval_date}}</code> - Tgl. Persetujuan<br>
                                        <code>{{current_date}}</code> - Tanggal Sekarang
                                    </div>
                                </div>
                                <div class="col-12">
                                    <strong class="text-dark">Lainnya:</strong>
                                    <div class="ms-2">
                                        <code>{{verification_url}}</code> - URL Verifikasi<br>
                                        <code>{{qr_code_url}}</code> - URL QR Code
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Content -->
            <div class="col-lg-8">
                <!-- Header Template -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-header me-2"></i>
                            Template Header
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control code-editor @error('header_template') is-invalid @enderror" 
                                  id="header_template" 
                                  name="header_template" 
                                  rows="10"
                                  placeholder="Template HTML untuk bagian header..."
                                  required>{{ old('header_template', $defaultTemplate['header_template']) }}</textarea>
                        @error('header_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Body Template -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-warning text-dark">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-align-left me-2"></i>
                            Template Body
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control code-editor @error('body_template') is-invalid @enderror" 
                                  id="body_template" 
                                  name="body_template" 
                                  rows="15"
                                  placeholder="Template HTML untuk bagian konten utama..."
                                  required>{{ old('body_template', $defaultTemplate['body_template']) }}</textarea>
                        @error('body_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Footer Template -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-secondary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-footer me-2"></i>
                            Template Footer
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control code-editor @error('footer_template') is-invalid @enderror" 
                                  id="footer_template" 
                                  name="footer_template" 
                                  rows="10"
                                  placeholder="Template HTML untuk bagian footer..."
                                  required>{{ old('footer_template', $defaultTemplate['footer_template']) }}</textarea>
                        @error('footer_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- CSS Styles -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-dark text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-paint-brush me-2"></i>
                            Custom CSS Styles
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control code-editor @error('css_styles') is-invalid @enderror" 
                                  id="css_styles" 
                                  name="css_styles" 
                                  rows="8"
                                  placeholder="CSS kustom untuk styling template...">{{ old('css_styles', $defaultTemplate['css_styles']) }}</textarea>
                        @error('css_styles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg me-2">
                            <i class="fas fa-save me-1"></i>
                            Simpan Template
                        </button>
                        <a href="{{ route('admin.loa-templates.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .code-editor {
        font-family: 'Courier New', monospace;
        font-size: 13px;
        line-height: 1.4;
    }
    
    .variable-list code {
        background-color: #f8f9fa;
        color: #e83e8c;
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 11px;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .form-label {
        color: #495057;
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    
    // Variable insertion helper
    const variableCodes = document.querySelectorAll('.variable-list code');
    variableCodes.forEach(code => {
        code.style.cursor = 'pointer';
        code.addEventListener('click', function() {
            const variable = this.textContent;
            // Get the currently focused textarea
            const activeElement = document.activeElement;
            if (activeElement && activeElement.tagName === 'TEXTAREA') {
                const start = activeElement.selectionStart;
                const end = activeElement.selectionEnd;
                const text = activeElement.value;
                activeElement.value = text.substring(0, start) + variable + text.substring(end);
                activeElement.focus();
                activeElement.setSelectionRange(start + variable.length, start + variable.length);
            }
        });
        
        // Add hover effect
        code.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#007bff';
            this.style.color = 'white';
        });
        
        code.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.color = '#e83e8c';
        });
    });
});
</script>
@endpush
