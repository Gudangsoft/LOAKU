@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>Add New Journal</h2>
    <a href="{{ route('publisher.journals') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Journals
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Journal Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('publisher.journals.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="publisher_id" class="form-label">Publisher <span class="text-danger">*</span></label>
                        <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                id="publisher_id" name="publisher_id" required>
                            <option value="">Select Publisher</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('publisher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($publishers->count() === 0)
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                You need to create a publisher first. 
                                <a href="{{ route('publisher.publishers.create') }}" class="text-primary">Create Publisher</a>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Journal Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="e_issn" class="form-label">Electronic ISSN</label>
                            <input type="text" class="form-control @error('e_issn') is-invalid @enderror" 
                                   id="e_issn" name="e_issn" value="{{ old('e_issn') }}" 
                                   placeholder="2580-1234">
                            @error('e_issn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="p_issn" class="form-label">Print ISSN</label>
                            <input type="text" class="form-control @error('p_issn') is-invalid @enderror" 
                                   id="p_issn" name="p_issn" value="{{ old('p_issn') }}" 
                                   placeholder="1234-5678">
                            @error('p_issn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="chief_editor" class="form-label">Chief Editor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('chief_editor') is-invalid @enderror" 
                               id="chief_editor" name="chief_editor" value="{{ old('chief_editor') }}" required>
                        @error('chief_editor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="website" class="form-label">Journal Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                               id="website" name="website" value="{{ old('website') }}" 
                               placeholder="https://journal.example.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="logo" class="form-label">Journal Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text">Max file size: 2MB. Allowed: JPG, PNG, GIF</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ttd_stample" class="form-label">Signature/Stamp</label>
                            <input type="file" class="form-control @error('ttd_stample') is-invalid @enderror" 
                                   id="ttd_stample" name="ttd_stample" accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text">For LOA certificates. Max: 2MB</div>
                            @error('ttd_stample')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('publisher.journals') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" {{ $publishers->count() === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i>Create Journal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Guidelines</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-info-circle text-info me-2"></i>Journal Requirements</h6>
                    <ul class="small">
                        <li>Journal name must be unique</li>
                        <li>At least one ISSN (Electronic or Print) is recommended</li>
                        <li>Chief Editor name is required for LOA documents</li>
                        <li>Website helps with journal credibility</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-certificate text-warning me-2"></i>ISSN Format</h6>
                    <ul class="small">
                        <li>Format: XXXX-XXXX</li>
                        <li>Example: 2580-1234</li>
                        <li>Electronic ISSN for online versions</li>
                        <li>Print ISSN for printed versions</li>
                    </ul>
                </div>
                
                <div class="alert alert-light">
                    <small class="text-muted">
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Tip:</strong> Journal information will appear on all LOA certificates issued for articles in this journal.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
