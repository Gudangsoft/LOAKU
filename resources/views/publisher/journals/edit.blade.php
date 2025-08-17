@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Journal</h2>
    <a href="{{ route('publisher.journals.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('publisher.journals.update', $journal) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Journal Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $journal->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publisher_id" class="form-label">Publisher <span class="text-danger">*</span></label>
                                <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                        id="publisher_id" name="publisher_id" required>
                                    <option value="">Select Publisher</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher->id }}" 
                                                {{ old('publisher_id', $journal->publisher_id) == $publisher->id ? 'selected' : '' }}>
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
                            <div class="mb-3">
                                <label for="chief_editor" class="form-label">Chief Editor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('chief_editor') is-invalid @enderror" 
                                       id="chief_editor" name="chief_editor" value="{{ old('chief_editor', $journal->chief_editor) }}" required>
                                @error('chief_editor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="e_issn" class="form-label">E-ISSN</label>
                                <input type="text" class="form-control @error('e_issn') is-invalid @enderror" 
                                       id="e_issn" name="e_issn" value="{{ old('e_issn', $journal->e_issn) }}" 
                                       placeholder="xxxx-xxxx">
                                @error('e_issn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="p_issn" class="form-label">P-ISSN</label>
                                <input type="text" class="form-control @error('p_issn') is-invalid @enderror" 
                                       id="p_issn" name="p_issn" value="{{ old('p_issn', $journal->p_issn) }}" 
                                       placeholder="xxxx-xxxx">
                                @error('p_issn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="website" class="form-label">Website URL</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                               id="website" name="website" value="{{ old('website', $journal->website) }}" 
                               placeholder="https://example.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $journal->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $journal->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Journal Logo</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" name="logo" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</div>
                                @if($journal->logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $journal->logo) }}" 
                                             class="img-thumbnail" style="max-height: 100px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="remove_logo" name="remove_logo" value="1">
                                            <label class="form-check-label" for="remove_logo">
                                                Remove current logo
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="signature_stamp" class="form-label">Signature Stamp</label>
                                <input type="file" class="form-control @error('signature_stamp') is-invalid @enderror" 
                                       id="signature_stamp" name="signature_stamp" accept="image/*">
                                @error('signature_stamp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">For LOA certificates. Maximum file size: 2MB.</div>
                                @if($journal->signature_stamp)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $journal->signature_stamp) }}" 
                                             class="img-thumbnail" style="max-height: 100px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="remove_signature" name="remove_signature" value="1">
                                            <label class="form-check-label" for="remove_signature">
                                                Remove current signature
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('publisher.journals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Journal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Journal Statistics</h5>
            </div>
            <div class="card-body">
                @php
                    $totalRequests = $journal->loaRequests()->count();
                    $pendingRequests = $journal->loaRequests()->where('status', 'pending')->count();
                    $approvedRequests = $journal->loaRequests()->where('status', 'approved')->count();
                    $rejectedRequests = $journal->loaRequests()->where('status', 'rejected')->count();
                @endphp

                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary mb-0">{{ $totalRequests }}</h4>
                            <small class="text-muted">Total LOA Requests</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-warning mb-0">{{ $pendingRequests }}</h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-success mb-0">{{ $approvedRequests }}</h4>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-danger mb-0">{{ $rejectedRequests }}</h4>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Created:</span>
                    <span>{{ $journal->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Last Updated:</span>
                    <span>{{ $journal->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('publisher.journals.show', $journal) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>View Details
                    </a>
                    <a href="{{ route('publisher.loa-requests.index') }}?journal={{ $journal->id }}" class="btn btn-outline-info">
                        <i class="fas fa-file-alt me-2"></i>View LOA Requests
                    </a>
                    @if($journal->website)
                        <a href="{{ $journal->website }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-2"></i>Visit Website
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
