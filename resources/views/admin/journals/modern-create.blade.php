@extends('admin.layouts.modern-app')

@section('title', 'Tambah Jurnal Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.journals.index') }}">Journals</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Create New</div>
                <h2 class="page-title">Journal Entry</h2>
                <p class="page-subtitle">Add a new scientific journal to the system</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.journals.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.journals.store') }}" enctype="multipart/form-data" id="journalForm">
        @csrf
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Basic Information
                        </h5>
                        <div class="card-subtitle">Essential journal details</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label required">Journal Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-book text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control form-control-modern @error('name') is-invalid @enderror" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Enter journal name..."
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">The official name of the journal as it appears in publications</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">E-ISSN</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-digital-tachograph text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control form-control-modern @error('e_issn') is-invalid @enderror" 
                                               name="e_issn" 
                                               value="{{ old('e_issn') }}" 
                                               placeholder="xxxx-xxxx"
                                               pattern="[0-9]{4}-[0-9]{4}">
                                    </div>
                                    @error('e_issn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">P-ISSN</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-print text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control form-control-modern @error('p_issn') is-invalid @enderror" 
                                               name="p_issn" 
                                               value="{{ old('p_issn') }}" 
                                               placeholder="xxxx-xxxx"
                                               pattern="[0-9]{4}-[0-9]{4}">
                                    </div>
                                    @error('p_issn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control form-control-modern @error('description') is-invalid @enderror" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Brief description of the journal's scope and focus...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Editorial Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2 text-success"></i>
                            Editorial Information
                        </h5>
                        <div class="card-subtitle">Editor and publisher details</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label required">Chief Editor</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-tie text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control form-control-modern @error('chief_editor') is-invalid @enderror" 
                                               name="chief_editor" 
                                               value="{{ old('chief_editor') }}" 
                                               placeholder="Enter chief editor name..."
                                               required>
                                    </div>
                                    @error('chief_editor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label required">Publisher</label>
                                    <select class="form-select form-select-modern @error('publisher_id') is-invalid @enderror" 
                                            name="publisher_id" 
                                            required>
                                        <option value="">Select Publisher</option>
                                        @foreach($publishers ?? [] as $publisher)
                                            <option value="{{ $publisher->id }}" 
                                                    {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('publisher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Website URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-globe text-muted"></i>
                                        </span>
                                        <input type="url" 
                                               class="form-control form-control-modern @error('website') is-invalid @enderror" 
                                               name="website" 
                                               value="{{ old('website') }}" 
                                               placeholder="https://journal-website.com">
                                    </div>
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control form-control-modern @error('email') is-invalid @enderror" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="journal@example.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publication Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar me-2 text-info"></i>
                            Publication Details
                        </h5>
                        <div class="card-subtitle">Publication frequency and indexing</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Publication Frequency</label>
                                    <select class="form-select form-select-modern" name="frequency">
                                        <option value="">Select Frequency</option>
                                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="bimonthly" {{ old('frequency') == 'bimonthly' ? 'selected' : '' }}>Bimonthly</option>
                                        <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="biannual" {{ old('frequency') == 'biannual' ? 'selected' : '' }}>Biannual</option>
                                        <option value="annual" {{ old('frequency') == 'annual' ? 'selected' : '' }}>Annual</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Subject Area</label>
                                    <input type="text" 
                                           class="form-control form-control-modern" 
                                           name="subject_area" 
                                           value="{{ old('subject_area') }}" 
                                           placeholder="e.g., Computer Science">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Language</label>
                                    <select class="form-select form-select-modern" name="language">
                                        <option value="Indonesian" {{ old('language') == 'Indonesian' ? 'selected' : '' }}>Indonesian</option>
                                        <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="Both" {{ old('language') == 'Both' ? 'selected' : '' }}>Both</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Indexing</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="indexing[]" value="DOAJ" id="doaj">
                                                <label class="form-check-label" for="doaj">DOAJ</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="indexing[]" value="Scopus" id="scopus">
                                                <label class="form-check-label" for="scopus">Scopus</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="indexing[]" value="SINTA" id="sinta">
                                                <label class="form-check-label" for="sinta">SINTA</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="indexing[]" value="Google Scholar" id="scholar">
                                                <label class="form-check-label" for="scholar">Google Scholar</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Action Card -->
                <div class="card sticky-top">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-modern">
                                <i class="fas fa-save me-2"></i>
                                Create Journal
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                <i class="fas fa-file-alt me-2"></i>
                                Save as Draft
                            </button>
                            <a href="{{ route('admin.journals.index') }}" class="btn btn-outline-danger">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select form-select-modern" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Logo Upload -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2 text-primary"></i>
                            Journal Logo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="upload-area" id="logoUpload">
                            <div class="upload-content">
                                <i class="fas fa-cloud-upload-alt mb-3" style="font-size: 2rem; color: #9ca3af;"></i>
                                <p class="mb-2">Drop logo here or <strong>click to browse</strong></p>
                                <small class="text-muted">PNG, JPG up to 2MB</small>
                            </div>
                            <input type="file" name="logo" accept="image/*" class="upload-input" id="logoInput">
                        </div>
                        
                        <div class="upload-preview" id="logoPreview" style="display: none;">
                            <img src="" alt="Logo preview" class="preview-image">
                            <div class="preview-actions">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLogo()">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2 text-info"></i>
                            Help & Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="help-item">
                            <div class="help-icon">
                                <i class="fas fa-lightbulb text-warning"></i>
                            </div>
                            <div class="help-content">
                                <strong>ISSN Format</strong>
                                <p>Use format: 1234-5678 for both E-ISSN and P-ISSN</p>
                            </div>
                        </div>
                        
                        <div class="help-item">
                            <div class="help-icon">
                                <i class="fas fa-info-circle text-info"></i>
                            </div>
                            <div class="help-content">
                                <strong>Logo Requirements</strong>
                                <p>Upload high-quality logo (PNG/JPG) for better branding</p>
                            </div>
                        </div>
                        
                        <div class="help-item">
                            <div class="help-icon">
                                <i class="fas fa-star text-success"></i>
                            </div>
                            <div class="help-content">
                                <strong>Best Practice</strong>
                                <p>Fill all fields completely for better LOA generation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    /* Modern Form Controls */
    .form-control-modern {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control-modern:focus {
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-select-modern {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-select-modern:focus {
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label.required::after {
        content: ' *';
        color: var(--danger-color);
    }

    .form-hint {
        color: #6b7280;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Input Groups */
    .input-group-text {
        background: #f8fafc;
        border: 2px solid #e5e7eb;
        border-right: none;
        color: #6b7280;
    }

    .input-group .form-control-modern {
        border-left: none;
    }

    .input-group .form-control-modern:focus {
        border-left-color: var(--primary-color);
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
        background: white;
    }

    /* Card Enhancements */
    .card-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Button Styles */
    .btn-modern {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Upload Area */
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        background: #f9fafb;
    }

    .upload-area:hover {
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.02);
    }

    .upload-area.dragover {
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.05);
    }

    .upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-content p {
        margin-bottom: 0.5rem;
        color: #6b7280;
    }

    /* Upload Preview */
    .upload-preview {
        text-align: center;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
    }

    .preview-image {
        max-width: 100%;
        max-height: 150px;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    /* Help Items */
    .help-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid #e5e7eb;
    }

    .help-item:last-child {
        margin-bottom: 0;
    }

    .help-icon {
        flex-shrink: 0;
        width: 24px;
        display: flex;
        justify-content: center;
    }

    .help-content strong {
        display: block;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        color: var(--dark-color);
    }

    .help-content p {
        font-size: 0.8rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    /* Form Check */
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-label {
        font-size: 0.9rem;
        color: var(--dark-color);
    }

    /* Sticky Sidebar */
    .sticky-top {
        top: 2rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .sticky-top {
            position: static !important;
        }
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    @keyframes spin {
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>
@endsection

@section('scripts')
<script>
    // Logo upload functionality
    const logoUpload = document.getElementById('logoUpload');
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');

    logoUpload.addEventListener('click', () => logoInput.click());

    logoUpload.addEventListener('dragover', (e) => {
        e.preventDefault();
        logoUpload.classList.add('dragover');
    });

    logoUpload.addEventListener('dragleave', () => {
        logoUpload.classList.remove('dragover');
    });

    logoUpload.addEventListener('drop', (e) => {
        e.preventDefault();
        logoUpload.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleLogoUpload(files[0]);
        }
    });

    logoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleLogoUpload(e.target.files[0]);
        }
    });

    function handleLogoUpload(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('File size should be less than 2MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const img = logoPreview.querySelector('.preview-image');
            img.src = e.target.result;
            logoUpload.style.display = 'none';
            logoPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    function removeLogo() {
        logoInput.value = '';
        logoUpload.style.display = 'block';
        logoPreview.style.display = 'none';
    }

    // Form submission
    document.getElementById('journalForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds to prevent permanent lock
        setTimeout(() => {
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        }, 5000);
    });

    // Save as draft
    function saveDraft() {
        const form = document.getElementById('journalForm');
        const statusInput = form.querySelector('select[name="status"]');
        statusInput.value = 'draft';
        form.submit();
    }

    // Auto-save functionality (optional)
    let autoSaveTimeout;
    const formInputs = document.querySelectorAll('#journalForm input, #journalForm select, #journalForm textarea');

    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Auto-save logic here
                console.log('Auto-saving form data...');
            }, 2000);
        });
    });

    // ISSN format validation
    document.querySelectorAll('input[pattern="[0-9]{4}-[0-9]{4}"]').forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 4) {
                value = value.substring(0, 4) + '-' + value.substring(4, 8);
            }
            this.value = value;
        });
    });

    // Form validation enhancement
    document.querySelectorAll('.form-control-modern, .form-select-modern').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endsection
