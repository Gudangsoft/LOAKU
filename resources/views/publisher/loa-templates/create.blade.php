@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-plus me-2"></i>Buat Template LOA Baru</h2>
        <p class="text-muted mb-0">Buat template surat penerimaan artikel (LOA) baru</p>
    </div>
    <a href="{{ route('publisher.loa-templates') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('publisher.loa-templates.store') }}" method="POST">
    @csrf
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
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Template <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Masukkan nama template" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publisher_info" class="form-label">Publisher</label>
                                <div class="form-control bg-light">
                                    {{ $publisher->name ?? 'Belum terdaftar sebagai publisher' }}
                                </div>
                                <small class="form-text text-muted">Template ini akan dibuat untuk publisher Anda</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Deskripsi template (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="language" class="form-label">Bahasa <span class="text-danger">*</span></label>
                                <select class="form-select @error('language') is-invalid @enderror" 
                                        id="language" name="language" required>
                                    <option value="">Pilih Bahasa</option>
                                    <option value="id" {{ old('language') == 'id' ? 'selected' : '' }}>Indonesia</option>
                                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="both" {{ old('language') == 'both' ? 'selected' : '' }}>Bilingual</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="format" class="form-label">Format <span class="text-danger">*</span></label>
                                <select class="form-select @error('format') is-invalid @enderror" 
                                        id="format" name="format" required>
                                    <option value="">Pilih Format</option>
                                    <option value="html" {{ old('format') == 'html' ? 'selected' : '' }}>HTML</option>
                                    <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                </select>
                                @error('format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                    <div class="mb-3">
                        <label for="header_template" class="form-label">Header Template</label>
                        <textarea class="form-control @error('header_template') is-invalid @enderror" 
                                  id="header_template" name="header_template" rows="5" 
                                  placeholder="HTML untuk header (opsional)">{{ old('header_template') }}</textarea>
                        @error('header_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body_template" class="form-label">Body Template <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('body_template') is-invalid @enderror" 
                                  id="body_template" name="body_template" rows="10" 
                                  placeholder="HTML untuk isi template" required>{{ old('body_template') }}</textarea>
                        @error('body_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="footer_template" class="form-label">Footer Template</label>
                        <textarea class="form-control @error('footer_template') is-invalid @enderror" 
                                  id="footer_template" name="footer_template" rows="5" 
                                  placeholder="HTML untuk footer (opsional)">{{ old('footer_template') }}</textarea>
                        @error('footer_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="css_styles" class="form-label">CSS Styles</label>
                        <textarea class="form-control @error('css_styles') is-invalid @enderror" 
                                  id="css_styles" name="css_styles" rows="8" 
                                  placeholder="CSS untuk styling template (opsional)">{{ old('css_styles') }}</textarea>
                        @error('css_styles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Pengaturan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Template Aktif
                        </label>
                        <small class="form-text text-muted">Template aktif dapat digunakan untuk generate LOA</small>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" 
                               {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">
                            Template Default
                        </label>
                        <small class="form-text text-muted">Template default akan digunakan secara otomatis</small>
                    </div>

                    <div class="mb-3">
                        <label for="variables" class="form-label">Variables (JSON)</label>
                        <textarea class="form-control @error('variables') is-invalid @enderror" 
                                  id="variables" name="variables" rows="6" 
                                  placeholder='{"key": "description"}'>{{ old('variables') }}</textarea>
                        @error('variables')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Define custom variables dalam format JSON</small>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Available Variables
                    </h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Variabel yang tersedia:</strong><br>
                        • <code>{&#123;article_title&#125;}</code> - Judul artikel<br>
                        • <code>{&#123;author_name&#125;}</code> - Nama penulis<br>
                        • <code>{&#123;registration_number&#125;}</code> - Nomor registrasi<br>
                        • <code>{&#123;publisher_name&#125;}</code> - Nama publisher<br>
                        • <code>{&#123;journal_name&#125;}</code> - Nama jurnal<br>
                        • <code>{&#123;submission_date&#125;}</code> - Tanggal submit<br>
                        • <code>{&#123;approval_date&#125;}</code> - Tanggal approve<br>
                        • <code>{&#123;website&#125;}</code> - Website<br>
                        • <code>{&#123;email&#125;}</code> - Email
                    </small>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Template
                        </button>
                        <a href="{{ route('publisher.loa-templates') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Add some basic template examples
document.addEventListener('DOMContentLoaded', function() {
    const languageSelect = document.getElementById('language');
    const bodyTemplate = document.getElementById('body_template');
    
    languageSelect.addEventListener('change', function() {
        if (bodyTemplate.value === '') {
            const language = this.value;
            let template = '';
            
            if (language === 'id') {
                template = `<div class="loa-content">
    <h2>SURAT PENERIMAAN ARTIKEL (LOA)</h2>
    <p>Dengan ini kami menyatakan bahwa artikel:</p>
    <table>
        <tr>
            <td width="150">Judul</td>
            <td>: {&#123;article_title&#125;}</td>
        </tr>
        <tr>
            <td>Penulis</td>
            <td>: {&#123;author_name&#125;}</td>
        </tr>
        <tr>
            <td>No. Registrasi</td>
            <td>: {&#123;registration_number&#125;}</td>
        </tr>
    </table>
    <p>Telah diterima untuk dipublikasikan di {&#123;journal_name&#125;}, {&#123;publisher_name&#125;}.</p>
    <p>Tanggal penerimaan: {&#123;approval_date&#125;}</p>
</div>`;
            } else if (language === 'en') {
                template = `<div class="loa-content">
    <h2>LETTER OF ACCEPTANCE (LOA)</h2>
    <p>We hereby state that the article:</p>
    <table>
        <tr>
            <td width="150">Title</td>
            <td>: {&#123;article_title&#125;}</td>
        </tr>
        <tr>
            <td>Author(s)</td>
            <td>: {&#123;author_name&#125;}</td>
        </tr>
        <tr>
            <td>Registration No.</td>
            <td>: {&#123;registration_number&#125;}</td>
        </tr>
    </table>
    <p>Has been accepted for publication in {&#123;journal_name&#125;}, {&#123;publisher_name&#125;}.</p>
    <p>Acceptance date: {&#123;approval_date&#125;}</p>
</div>`;
            }
            
            if (template) {
                bodyTemplate.value = template;
            }
        }
    });
});
</script>
@endsection
