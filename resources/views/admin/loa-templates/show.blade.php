@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>LOA Template: {{ $template->name }}</h4>
                    <div>
                        <a href="{{ route('admin.loa-templates.edit', $template->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.loa-templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Name:</th>
                                    <td>{{ $template->name }}</td>
                                </tr>
                                <tr>
                                    <th>Language:</th>
                                    <td>
                                        @switch($template->language)
                                            @case('id')
                                                <span class="badge badge-info">Indonesian</span>
                                                @break
                                            @case('en')
                                                <span class="badge badge-success">English</span>
                                                @break
                                            @case('both')
                                                <span class="badge badge-primary">Bilingual</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $template->language }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Publisher:</th>
                                    <td>
                                        @if($template->publisher)
                                            {{ $template->publisher->name }}
                                        @else
                                            <em>All Publishers</em>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Default:</th>
                                    <td>
                                        @if($template->is_default)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($template->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $template->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $template->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($template->description)
                                <div class="form-group">
                                    <label><strong>Description:</strong></label>
                                    <p class="text-muted">{{ $template->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Preview -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Template Preview</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-info" onclick="toggleRawTemplate()">
                            <i class="fas fa-code"></i> Toggle Raw Template
                        </button>
                        <a href="{{ route('admin.loa-templates.preview', $template->id) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt"></i> Open in New Window
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Rendered Preview -->
                    <div id="rendered-preview">
                        <div class="border p-4" style="background: white; min-height: 500px;">
                            {!! $renderedTemplate !!}
                        </div>
                    </div>

                    <!-- Raw Template -->
                    <div id="raw-template" style="display: none;">
                        <div class="row">
                            @if($template->header_template)
                                <div class="col-md-12 mb-3">
                                    <h6>Header Template:</h6>
                                    <pre class="bg-light p-3 border rounded"><code>{{ $template->header_template }}</code></pre>
                                </div>
                            @endif
                            
                            <div class="col-md-12 mb-3">
                                <h6>Body Template:</h6>
                                <pre class="bg-light p-3 border rounded"><code>{{ $template->body_template }}</code></pre>
                            </div>
                            
                            @if($template->footer_template)
                                <div class="col-md-12 mb-3">
                                    <h6>Footer Template:</h6>
                                    <pre class="bg-light p-3 border rounded"><code>{{ $template->footer_template }}</code></pre>
                                </div>
                            @endif
                            
                            @if($template->css_styles)
                                <div class="col-md-12 mb-3">
                                    <h6>CSS Styles:</h6>
                                    <pre class="bg-light p-3 border rounded"><code>{{ $template->css_styles }}</code></pre>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Variables Used -->
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

<script>
function toggleRawTemplate() {
    const rendered = document.getElementById('rendered-preview');
    const raw = document.getElementById('raw-template');
    
    if (raw.style.display === 'none') {
        rendered.style.display = 'none';
        raw.style.display = 'block';
    } else {
        rendered.style.display = 'block';
        raw.style.display = 'none';
    }
}
</script>
@endsection
