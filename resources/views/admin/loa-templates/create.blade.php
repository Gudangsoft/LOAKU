@extends('layouts.admin')

@section('title', 'Create LOA Template')
@section('subtitle', 'Add new LOA document template')

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
            <!-- Form Column - Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Dasar
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            
                            <div class="col-md-6">
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
                                    <label for="publisher_id" class="form-label fw-bold">Publisher</label>
                                    <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                            id="publisher_id" 
                                            name="publisher_id">
                                        <option value="">Global (Semua Publisher)</option>
                                        @if(isset($publishers))
                                            @foreach($publishers as $publisher)
                                                <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                                    {{ $publisher->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('publisher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Kosongkan untuk template global</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
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
                    </div>
                </div>

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
                                  required>{{ old('header_template', isset($defaultTemplate) ? $defaultTemplate['header_template'] : '') }}</textarea>
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
                                  required>{{ old('body_template', isset($defaultTemplate) ? $defaultTemplate['body_template'] : '') }}</textarea>
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
                                  required>{{ old('footer_template', isset($defaultTemplate) ? $defaultTemplate['footer_template'] : '') }}</textarea>
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
                                  placeholder="CSS kustom untuk styling template...">{{ old('css_styles', isset($defaultTemplate) ? $defaultTemplate['css_styles'] : '') }}</textarea>
                        @error('css_styles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card shadow mb-4">
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

            <!-- Variables Helper Column -->
            <div class="col-lg-4">
                <!-- Available Variables -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-tags me-2"></i>
                            Variabel Tersedia
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="variable-list" style="max-height: 600px; overflow-y: auto;">
                            <div class="small">
                                <div class="mb-3">
                                    <strong class="text-primary">Publisher:</strong>
                                    <div class="ms-2">
                                        <code class="var-code">{<!-- -->{publisher_name}}</code> - Nama Penerbit<br>
                                        <code class="var-code">{<!-- -->{publisher_address}}</code> - Alamat<br>
                                        <code class="var-code">{<!-- -->{publisher_email}}</code> - Email<br>
                                        <code class="var-code">{<!-- -->{publisher_phone}}</code> - Telepon
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong class="text-success">Jurnal:</strong>
                                    <div class="ms-2">
                                        <code class="var-code">{<!-- -->{journal_name}}</code> - Nama Jurnal<br>
                                        <code class="var-code">{<!-- -->{journal_issn_e}}</code> - E-ISSN<br>
                                        <code class="var-code">{<!-- -->{journal_issn_p}}</code> - P-ISSN<br>
                                        <code class="var-code">{<!-- -->{chief_editor}}</code> - Pemimpin Redaksi
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong class="text-warning">LOA:</strong>
                                    <div class="ms-2">
                                        <code class="var-code">{<!-- -->{loa_code}}</code> - Kode LOA<br>
                                        <code class="var-code">{<!-- -->{article_title}}</code> - Judul Artikel<br>
                                        <code class="var-code">{<!-- -->{author_name}}</code> - Nama Penulis<br>
                                        <code class="var-code">{<!-- -->{author_email}}</code> - Email Penulis
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong class="text-secondary">Publikasi:</strong>
                                    <div class="ms-2">
                                        <code class="var-code">{<!-- -->{volume}}</code> - Volume<br>
                                        <code class="var-code">{<!-- --}{number}}</code> - Nomor<br>
                                        <code class="var-code">{<!-- -->{month}}</code> - Bulan<br>
                                        <code class="var-code">{<!-- -->{year}}</code> - Tahun<br>
                                        <code class="var-code">{<!-- --}{registration_number}}</code> - No. Reg
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong class="text-danger">Tanggal:</strong>
                                    <div class="ms-2">
                                        <code class="var-code">{<!-- --}{approval_date}}</code> - Tgl. Persetujuan<br>
                                        <code class="var-code">{<!-- --}{current_date}}</code> - Tanggal Sekarang
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong class="text-dark">Lainnya:</strong>
                                    <div class="ms-2">
                                        <code class="var-code">{<!-- -->{verification_url}}</code> - URL Verifikasi<br>
                                        <code class="var-code">{<!-- --}{qr_code_url}}</code> - URL QR Code
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Klik variabel untuk copy ke clipboard
                            </small>
                        </div>
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
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .code-editor:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .var-code {
        background-color: #f8f9fa !important;
        color: #e83e8c !important;
        padding: 3px 6px !important;
        border-radius: 4px !important;
        font-size: 11px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        border: 1px solid #dee2e6 !important;
        display: inline-block !important;
        margin: 1px !important;
    }
    
    .var-code:hover {
        background-color: #007bff !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,123,255,0.3);
    }
    
    .var-code:active {
        transform: translateY(0);
        background-color: #28a745 !important;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        transition: all 0.3s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2) !important;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .form-label {
        color: #495057;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn {
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Scrollbar Styling */
    .variable-list::-webkit-scrollbar {
        width: 8px;
    }
    
    .variable-list::-webkit-scrollbar-track {
        background: #f1f3f4;
        border-radius: 4px;
    }
    
    .variable-list::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #17a2b8, #138496);
        border-radius: 4px;
    }
    
    .variable-list::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #138496, #117a8b);
    }
    
    /* Animation for successful copy */
    @keyframes copySuccess {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .copy-success {
        animation: copySuccess 0.3s ease;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .col-lg-8, .col-lg-4 {
            margin-bottom: 1.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea.code-editor');
    textareas.forEach(textarea => {
        // Initial resize
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
        
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    
    // Variable insertion helper with improved UX
    const variableCodes = document.querySelectorAll('.var-code');
    variableCodes.forEach(code => {
        code.addEventListener('click', function() {
            const variable = this.textContent;
            
            // Copy to clipboard with modern API
            if (navigator.clipboard) {
                navigator.clipboard.writeText(variable).then(function() {
                    showCopyFeedback(code, variable);
                }).catch(function() {
                    // Fallback for older browsers
                    fallbackCopyToClipboard(variable, code);
                });
            } else {
                fallbackCopyToClipboard(variable, code);
            }
            
            // Also try to insert into active textarea
            const activeElement = document.activeElement;
            if (activeElement && activeElement.tagName === 'TEXTAREA') {
                const start = activeElement.selectionStart;
                const end = activeElement.selectionEnd;
                const text = activeElement.value;
                
                // Insert variable at cursor position
                activeElement.value = text.substring(0, start) + variable + text.substring(end);
                
                // Restore focus and cursor position
                activeElement.focus();
                const newPos = start + variable.length;
                activeElement.setSelectionRange(newPos, newPos);
                
                // Trigger input event for auto-resize
                activeElement.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });
    
    // Show copy feedback
    function showCopyFeedback(element, variable) {
        const originalText = element.textContent;
        const originalBg = element.style.backgroundColor;
        const originalColor = element.style.color;
        
        // Add success animation
        element.classList.add('copy-success');
        element.textContent = '✓ Copied!';
        element.style.backgroundColor = '#28a745';
        element.style.color = 'white';
        element.style.fontWeight = 'bold';
        
        setTimeout(function() {
            element.classList.remove('copy-success');
            element.textContent = originalText;
            element.style.backgroundColor = originalBg;
            element.style.color = originalColor;
            element.style.fontWeight = 'normal';
        }, 1200);
    }
    
    // Fallback copy function for older browsers
    function fallbackCopyToClipboard(text, element) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showCopyFeedback(element, text);
        } catch (err) {
            console.error('Could not copy text: ', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    // Enhanced form validation feedback
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first invalid field
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            }
        });
    }
    
    // Real-time validation for required fields
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
    });
});
</script>
@endpush
