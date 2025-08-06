@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-edit me-2"></i>Edit Template LOA</h2>
        <p class="text-muted mb-0">{{ $loaTemplate->name }}</p>
    </div>
    <a href="{{ route('publisher.loa-templates.show', $loaTemplate) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('publisher.loa-templates.update', $loaTemplate) }}" method="POST">
    @csrf
    @method('PUT')
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
                                       id="name" name="name" value="{{ old('name', $loaTemplate->name) }}" 
                                       placeholder="Masukkan nama template" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publisher_id" class="form-label">Publisher</label>
                                <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                        id="publisher_id" name="publisher_id">
                                    <option value="">Pilih Publisher (Opsional)</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher->id }}" 
                                                {{ old('publisher_id', $loaTemplate->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                            {{ $publisher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('publisher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Template akan berlaku untuk publisher tertentu jika dipilih</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Deskripsi template (opsional)">{{ old('description', $loaTemplate->description) }}</textarea>
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
                                    <option value="id" {{ old('language', $loaTemplate->language) == 'id' ? 'selected' : '' }}>Indonesia</option>
                                    <option value="en" {{ old('language', $loaTemplate->language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="both" {{ old('language', $loaTemplate->language) == 'both' ? 'selected' : '' }}>Bilingual</option>
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
                                    <option value="html" {{ old('format', $loaTemplate->format) == 'html' ? 'selected' : '' }}>HTML</option>
                                    <option value="pdf" {{ old('format', $loaTemplate->format) == 'pdf' ? 'selected' : '' }}>PDF</option>
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
                                  placeholder="HTML untuk header (opsional)">{{ old('header_template', $loaTemplate->header_template) }}</textarea>
                        @error('header_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body_template" class="form-label">Body Template <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('body_template') is-invalid @enderror" 
                                  id="body_template" name="body_template" rows="10" 
                                  placeholder="HTML untuk isi template" required>{{ old('body_template', $loaTemplate->body_template) }}</textarea>
                        @error('body_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="footer_template" class="form-label">Footer Template</label>
                        <textarea class="form-control @error('footer_template') is-invalid @enderror" 
                                  id="footer_template" name="footer_template" rows="5" 
                                  placeholder="HTML untuk footer (opsional)">{{ old('footer_template', $loaTemplate->footer_template) }}</textarea>
                        @error('footer_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="css_styles" class="form-label">CSS Styles</label>
                        <textarea class="form-control @error('css_styles') is-invalid @enderror" 
                                  id="css_styles" name="css_styles" rows="8" 
                                  placeholder="CSS untuk styling template (opsional)">{{ old('css_styles', $loaTemplate->css_styles) }}</textarea>
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
                               {{ old('is_active', $loaTemplate->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Template Aktif
                        </label>
                        <small class="form-text text-muted">Template aktif dapat digunakan untuk generate LOA</small>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" 
                               {{ old('is_default', $loaTemplate->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">
                            Template Default
                        </label>
                        <small class="form-text text-muted">Template default akan digunakan secara otomatis</small>
                    </div>

                    <div class="mb-3">
                        <label for="variables" class="form-label">Variables (JSON)</label>
                        <textarea class="form-control @error('variables') is-invalid @enderror" 
                                  id="variables" name="variables" rows="6" 
                                  placeholder='{"key": "description"}'>{{ old('variables', $loaTemplate->variables ? json_encode($loaTemplate->variables, JSON_PRETTY_PRINT) : '') }}</textarea>
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
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Template
                        </button>
                        <a href="{{ route('publisher.loa-templates.preview', $loaTemplate) }}" 
                           class="btn btn-info" target="_blank">
                            <i class="fas fa-search me-2"></i>Preview Template
                        </a>
                        <a href="{{ route('publisher.loa-templates.show', $loaTemplate) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
