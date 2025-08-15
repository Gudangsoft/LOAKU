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
            <!-- Form Column - Left Side (8 columns) -->
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

            <!-- Tutorial & Helper Column - Right Side (4 columns) -->
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
                        <div class="variable-list" style="max-height: 400px; overflow-y: auto;">
                            <div class="row small">
                                <div class="col-12 mb-2">
                                    <strong class="text-primary">Publisher:</strong>
                                    <div class="ms-2">
                                        <code>{<!-- -->{publisher_name}}</code> - Nama Penerbit<br>
                                        <code>{<!-- -->{publisher_address}}</code> - Alamat<br>
                                        <code>{<!-- -->{publisher_email}}</code> - Email<br>
                                        <code>{<!-- -->{publisher_phone}}</code> - Telepon
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-success">Jurnal:</strong>
                                    <div class="ms-2">
                                        <code>{<!-- -->{journal_name}}</code> - Nama Jurnal<br>
                                        <code>{<!-- -->{journal_issn_e}}</code> - E-ISSN<br>
                                        <code>{<!-- -->{journal_issn_p}}</code> - P-ISSN<br>
                                        <code>{<!-- -->{chief_editor}}</code> - Pemimpin Redaksi
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-warning">LOA:</strong>
                                    <div class="ms-2">
                                        <code>{<!-- -->{loa_code}}</code> - Kode LOA<br>
                                        <code>{<!-- -->{article_title}}</code> - Judul Artikel<br>
                                        <code>{<!-- -->{author_name}}</code> - Nama Penulis<br>
                                        <code>{<!-- -->{author_email}}</code> - Email Penulis
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-secondary">Publikasi:</strong>
                                    <div class="ms-2">
                                        <code>{<!-- -->{volume}}</code> - Volume<br>
                                        <code>{<!-- -->{number}}</code> - Nomor<br>
                                        <code>{<!-- -->{month}}</code> - Bulan<br>
                                        <code>{<!-- -->{year}}</code> - Tahun<br>
                                        <code>{<!-- -->{registration_number}}</code> - No. Reg
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong class="text-danger">Tanggal:</strong>
                                    <div class="ms-2">
                                        <code>{<!-- -->{approval_date}}</code> - Tgl. Persetujuan<br>
                                        <code>{<!-- -->{current_date}}</code> - Tanggal Sekarang
                                    </div>
                                </div>
                                <div class="col-12">
                                    <strong class="text-dark">Lainnya:</strong>
                                    <div class="ms-2">
                                        <code>{<!-- -->{verification_url}}</code> - URL Verifikasi<br>
                                        <code>{<!-- -->{qr_code_url}}</code> - URL QR Code
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tutorial & Cara Penggunaan -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-book me-2"></i>
                            Tutorial & Cara Penggunaan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="tutorial-content" style="max-height: 600px; overflow-y: auto;">
                            
                            <!-- Quick Start Guide -->
                            <div class="tutorial-section border-left-primary mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-rocket me-2 text-primary"></i>
                                    <strong>Quick Start Guide</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="step-list">
                                        <div class="step-item">
                                            <div class="step-number">1</div>
                                            <span>Isi <strong>nama template</strong> dan <strong>deskripsi</strong></span>
                                        </div>
                                        <div class="step-item">
                                            <div class="step-number">2</div>
                                            <span>Pilih <strong>bahasa</strong> dan <strong>format</strong> output</span>
                                        </div>
                                        <div class="step-item">
                                            <div class="step-number">3</div>
                                            <span>Pilih <strong>publisher</strong> (opsional)</span>
                                        </div>
                                        <div class="step-item">
                                            <div class="step-number">4</div>
                                            <span>Edit <strong>Header, Body, Footer</strong> template</span>
                                        </div>
                                        <div class="step-item">
                                            <div class="step-number">5</div>
                                            <span>Tambahkan <strong>CSS custom</strong> jika perlu</span>
                                        </div>
                                        <div class="step-item">
                                            <div class="step-number">6</div>
                                            <span>Klik <strong>Simpan Template</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Template Structure -->
                            <div class="tutorial-section border-left-success mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-code me-2 text-success"></i>
                                    <strong>Struktur Template</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="structure-grid">
                                        <div class="structure-item">
                                            <div class="structure-icon bg-gradient-primary">
                                                <i class="fas fa-header"></i>
                                            </div>
                                            <div class="structure-content">
                                                <strong>Header</strong>
                                                <small>Logo, judul, info publisher</small>
                                            </div>
                                        </div>
                                        <div class="structure-item">
                                            <div class="structure-icon" style="background: #28a745;">
                                                <i class="fas fa-align-left"></i>
                                            </div>
                                            <div class="structure-content">
                                                <strong>Body</strong>
                                                <small>Konten LOA utama</small>
                                            </div>
                                        </div>
                                        <div class="structure-item">
                                            <div class="structure-icon" style="background: #6c757d;">
                                                <i class="fas fa-footer"></i>
                                            </div>
                                            <div class="structure-content">
                                                <strong>Footer</strong>
                                                <small>Tanda tangan, QR code, verifikasi</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variable Usage -->
                            <div class="tutorial-section border-left-warning mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-variable me-2 text-warning"></i>
                                    <strong>Menggunakan Variabel</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="variable-format">
                                        <div class="format-label">Format:</div>
                                        <div class="format-example">
                                            <code>{<!-- -->{nama_variabel}}</code>
                                        </div>
                                    </div>
                                    <div class="code-examples mt-3">
                                        <div class="code-example">
                                            <code>&lt;h1&gt;{<!-- -->{publisher_name}}&lt;/h1&gt;</code>
                                        </div>
                                        <div class="code-example">
                                            <code>&lt;p&gt;Dear {<!-- -->{author_name}},&lt;/p&gt;</code>
                                        </div>
                                        <div class="code-example">
                                            <code>&lt;p&gt;Article: {<!-- -->{article_title}}&lt;/p&gt;</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- HTML Tips -->
                            <div class="tutorial-section border-left-info mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-code me-2 text-info"></i>
                                    <strong>Tips HTML</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="tips-grid">
                                        <div class="tip-item">
                                            <code>&lt;div&gt;</code>
                                            <span>untuk layout</span>
                                        </div>
                                        <div class="tip-item">
                                            <code>&lt;table&gt;</code>
                                            <span>untuk data terstruktur</span>
                                        </div>
                                        <div class="tip-item">
                                            <code>&lt;img&gt;</code>
                                            <span>untuk logo/gambar</span>
                                        </div>
                                        <div class="tip-item">
                                            <code>&lt;br&gt;</code>
                                            <span>untuk line break</span>
                                        </div>
                                        <div class="tip-item">
                                            <code>&lt;strong&gt;</code>
                                            <span>untuk text bold</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CSS Tips -->
                            <div class="tutorial-section border-left-secondary mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-paint-brush me-2 text-secondary"></i>
                                    <strong>Tips CSS</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="css-examples">
                                        <div class="css-example">
                                            <code>body { font-family: Arial; }</code>
                                        </div>
                                        <div class="css-example">
                                            <code>.header { text-align: center; }</code>
                                        </div>
                                        <div class="css-example">
                                            <code>.signature { margin-top: 50px; }</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Best Practices -->
                            <div class="tutorial-section border-left-danger mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-lightbulb me-2 text-danger"></i>
                                    <strong>Best Practices</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="practices-list">
                                        <div class="practice-item">
                                            <i class="fas fa-mobile-alt text-primary"></i>
                                            <div>
                                                <strong>Responsive Design</strong>
                                                <span>Gunakan % untuk width</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <div>
                                                <strong>PDF Compatible</strong>
                                                <span>Hindari CSS kompleks untuk format PDF</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-font text-info"></i>
                                            <div>
                                                <strong>Font Safe</strong>
                                                <span>Gunakan web-safe fonts</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-eye text-success"></i>
                                            <div>
                                                <strong>Test Preview</strong>
                                                <span>Selalu test template sebelum menyimpan</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-save text-warning"></i>
                                            <div>
                                                <strong>Backup</strong>
                                                <span>Simpan copy template penting</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Template Examples -->
                            <div class="tutorial-section border-left-dark mb-3">
                                <div class="tutorial-header">
                                    <i class="fas fa-file-code me-2 text-dark"></i>
                                    <strong>Contoh Template</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="template-examples">
                                        <div class="template-example">
                                            <div class="example-label">Header Example</div>
                                            <div class="example-code">
                                                <code>&lt;div class="header"&gt;
  &lt;h1&gt;{<!-- -->{publisher_name}}&lt;/h1&gt;
  &lt;p&gt;{<!-- -->{publisher_address}}&lt;/p&gt;
&lt;/div&gt;</code>
                                            </div>
                                        </div>
                                        
                                        <div class="template-example">
                                            <div class="example-label">Body Example</div>
                                            <div class="example-code">
                                                <code>&lt;div class="content"&gt;
  &lt;h2&gt;Letter of Acceptance&lt;/h2&gt;
  &lt;p&gt;Dear {<!-- -->{author_name}},&lt;/p&gt;
  &lt;p&gt;Your article "{<!-- -->{article_title}}" has been accepted.&lt;/p&gt;
&lt;/div&gt;</code>
                                            </div>
                                        </div>
                                        
                                        <div class="template-example">
                                            <div class="example-label">Footer Example</div>
                                            <div class="example-code">
                                                <code>&lt;div class="footer"&gt;
  &lt;p&gt;Date: {<!-- -->{current_date}}&lt;/p&gt;
  &lt;p&gt;LOA Code: {<!-- -->{loa_code}}&lt;/p&gt;
&lt;/div&gt;</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Troubleshooting -->
                            <div class="tutorial-section border-left-warning">
                                <div class="tutorial-header">
                                    <i class="fas fa-question-circle me-2 text-warning"></i>
                                    <strong>Troubleshooting</strong>
                                </div>
                                <div class="tutorial-body">
                                    <div class="practices-list">
                                        <div class="practice-item">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                            <div>
                                                <strong>Variabel tidak muncul</strong>
                                                <span>Periksa penulisan {<!-- -->{variable}}</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-code text-danger"></i>
                                            <div>
                                                <strong>Layout berantakan</strong>
                                                <span>Periksa tag HTML yang tidak tertutup</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-file-pdf text-info"></i>
                                            <div>
                                                <strong>PDF error</strong>
                                                <span>Hindari CSS kompleks, gunakan inline style</span>
                                            </div>
                                        </div>
                                        <div class="practice-item">
                                            <i class="fas fa-font text-secondary"></i>
                                            <div>
                                                <strong>Font tidak muncul</strong>
                                                <span>Gunakan web-safe fonts saja</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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

    /* Tutorial Styling - Enhanced */
    .tutorial-content {
        font-size: 0.875rem;
        line-height: 1.5;
        max-height: 600px;
        overflow-y: auto;
        padding: 0;
    }
    
    .tutorial-section {
        border-left: 4px solid #e9ecef;
        margin-bottom: 0;
        background: #fff;
        transition: all 0.2s ease;
    }
    
    .tutorial-section:hover {
        background: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .tutorial-header {
        display: flex;
        align-items: center;
        padding: 15px 20px 10px;
        margin-bottom: 0;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f3f4;
        background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(248,249,250,0.5));
    }
    
    .tutorial-body {
        padding: 15px 20px 20px;
    }
    
    /* Step List Styling */
    .step-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .step-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    
    .step-item:hover {
        background: #e3f2fd;
        border-color: #2196F3;
    }
    
    .step-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    /* Structure Grid */
    .structure-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .structure-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    .structure-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .structure-content strong {
        display: block;
        font-size: 0.85rem;
        margin-bottom: 2px;
    }
    
    .structure-content small {
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    /* Variable Format */
    .variable-format {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #fff3cd;
        border-radius: 8px;
        border: 1px solid #ffeaa7;
    }
    
    .format-label {
        font-weight: 600;
        color: #856404;
    }
    
    .format-example code {
        background: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        color: #d63384;
        border: 1px solid #e9ecef;
    }
    
    /* Code Examples */
    .code-examples {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .code-example {
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #ffc107;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
    }
    
    .code-example code {
        background: none;
        padding: 0;
        color: #d63384;
        font-weight: 500;
    }
    
    /* Tips Grid */
    .tips-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .tip-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #e8f4fd;
        border-radius: 6px;
        border-left: 3px solid #17a2b8;
    }
    
    .tip-item code {
        background: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        color: #e83e8c;
        font-weight: 600;
        min-width: 70px;
        text-align: center;
    }
    
    .tip-item span {
        color: #495057;
        font-size: 0.8rem;
    }
    
    /* CSS Examples */
    .css-examples {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .css-example {
        padding: 10px 15px;
        background: #f1f3f4;
        border-radius: 6px;
        border-left: 3px solid #6c757d;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
    }
    
    .css-example code {
        background: none;
        padding: 0;
        color: #495057;
        font-weight: 500;
    }
    
    /* Practices List */
    .practices-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .practice-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        background: #fff5f5;
        border-radius: 8px;
        border: 1px solid #fed7d7;
    }
    
    .practice-item i {
        margin-top: 2px;
        font-size: 14px;
    }
    
    .practice-item strong {
        display: block;
        margin-bottom: 3px;
        font-size: 0.85rem;
    }
    
    .practice-item span {
        color: #6c757d;
        font-size: 0.8rem;
    }
    
    /* Template Examples */
    .template-examples {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .template-example {
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .example-label {
        padding: 8px 15px;
        background: #343a40;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .example-code {
        padding: 15px;
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        line-height: 1.6;
    }
    
    .example-code code {
        background: none;
        padding: 0;
        color: #495057;
        white-space: pre-wrap;
    }
    
    /* Border Colors */
    .border-left-primary { border-left-color: #007bff !important; }
    .border-left-success { border-left-color: #28a745 !important; }
    .border-left-warning { border-left-color: #ffc107 !important; }
    .border-left-info { border-left-color: #17a2b8 !important; }
    .border-left-secondary { border-left-color: #6c757d !important; }
    .border-left-danger { border-left-color: #dc3545 !important; }
    .border-left-dark { border-left-color: #343a40 !important; }
    
    /* Background Gradients */
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3) !important;
    }
    
    /* Scrollbar Styling */
    .tutorial-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .tutorial-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .tutorial-content::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #007bff, #0056b3);
        border-radius: 4px;
    }
    
    .tutorial-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #0056b3, #004085);
    }
    
    /* Variable List Styling */
    .variable-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .variable-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .variable-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .variable-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .tutorial-content {
            max-height: 400px;
        }
        
        .tutorial-header {
            padding: 12px 15px 8px;
        }
        
        .tutorial-body {
            padding: 12px 15px 15px;
        }
        
        .step-item, .structure-item, .practice-item {
            padding: 8px 12px;
        }
        
        .col-lg-8, .col-lg-4 {
            margin-bottom: 2rem;
        }
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
