@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit LOA Template</h4>
                    <a href="{{ route('admin.loa-templates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.loa-templates.update', $template->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Template Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $template->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">Language <span class="text-danger">*</span></label>
                                    <select class="form-control @error('language') is-invalid @enderror" 
                                            id="language" name="language" required>
                                        <option value="">Select Language</option>
                                        <option value="id" {{ old('language', $template->language) == 'id' ? 'selected' : '' }}>Indonesian</option>
                                        <option value="en" {{ old('language', $template->language) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="both" {{ old('language', $template->language) == 'both' ? 'selected' : '' }}>Bilingual</option>
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="publisher_id">Publisher (Optional)</label>
                                    <select class="form-control @error('publisher_id') is-invalid @enderror" 
                                            id="publisher_id" name="publisher_id">
                                        <option value="">All Publishers</option>
                                        @foreach($publishers as $publisher)
                                            <option value="{{ $publisher->id }}" 
                                                {{ old('publisher_id', $template->publisher_id) == $publisher->id ? 'selected' : '' }}>
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
                                    <label for="is_default">Default Template</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               id="is_default" name="is_default" value="1"
                                               {{ old('is_default', $template->is_default) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Use as default template
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $template->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="header_template">Header Template</label>
                            <textarea class="form-control template-editor @error('header_template') is-invalid @enderror" 
                                      id="header_template" name="header_template" rows="8">{{ old('header_template', $template->header_template) }}</textarea>
                            @error('header_template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="body_template">Body Template <span class="text-danger">*</span></label>
                            <textarea class="form-control template-editor @error('body_template') is-invalid @enderror" 
                                      id="body_template" name="body_template" rows="15" required>{{ old('body_template', $template->body_template) }}</textarea>
                            @error('body_template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="footer_template">Footer Template</label>
                            <textarea class="form-control template-editor @error('footer_template') is-invalid @enderror" 
                                      id="footer_template" name="footer_template" rows="6">{{ old('footer_template', $template->footer_template) }}</textarea>
                            @error('footer_template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="css_styles">Custom CSS Styles</label>
                            <textarea class="form-control @error('css_styles') is-invalid @enderror" 
                                      id="css_styles" name="css_styles" rows="8">{{ old('css_styles', $template->css_styles) }}</textarea>
                            @error('css_styles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Template
                                        </button>
                                        <a href="{{ route('admin.loa-templates.show', $template->id) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                    </div>
                                    <a href="{{ route('admin.loa-templates.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Available Variables Reference -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Available Template Variables</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Variables:</h6>
                            <ul class="list-unstyled">
                                <li><code>{{title}}</code> - LOA Title</li>
                                <li><code>{{author_name}}</code> - Author Name</li>
                                <li><code>{{author_email}}</code> - Author Email</li>
                                <li><code>{{publisher_name}}</code> - Publisher Name</li>
                                <li><code>{{publication_date}}</code> - Publication Date</li>
                                <li><code>{{verification_date}}</code> - Verification Date</li>
                                <li><code>{{qr_code_url}}</code> - QR Code URL</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Conditional Blocks:</h6>
                            <ul class="list-unstyled">
                                <li><code>{{#if_id}} ... {{/if_id}}</code> - Indonesian content</li>
                                <li><code>{{#if_en}} ... {{/if_en}}</code> - English content</li>
                                <li><code>{{#if_both}} ... {{/if_both}}</code> - Bilingual content</li>
                            </ul>
                            <h6>Advanced Variables:</h6>
                            <ul class="list-unstyled">
                                <li><code>{{current_date}}</code> - Current Date</li>
                                <li><code>{{verification_code}}</code> - Verification Code</li>
                                <li><code>{{document_type}}</code> - Document Type</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.template-editor {
    font-family: 'Courier New', monospace;
    font-size: 14px;
}
</style>
@endsection
