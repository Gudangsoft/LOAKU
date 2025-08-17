@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-building me-2"></i>Publisher Management</h2>
    <a href="{{ route('publisher.publishers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Publisher
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">My Publishers</h5>
    </div>
    <div class="card-body">
        @if($publishers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Publisher Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Website</th>
                            <th>Journals</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publishers as $publisher)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($publisher->logo)
                                        <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                             class="me-2 rounded" width="32" height="32">
                                    @else
                                        <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($publisher->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $publisher->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $publisher->email }}</div>
                                <small class="text-muted">{{ $publisher->phone }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($publisher->address, 50) }}</small>
                            </td>
                            <td>
                                @if($publisher->website)
                                    <a href="{{ $publisher->website }}" target="_blank" class="text-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $publisher->journals()->count() }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $publisher->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('publisher.publishers.edit', $publisher) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="{{ route('publisher.publishers.show', $publisher) }}"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deletePublisher({{ $publisher->id }})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $publishers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Publishers Yet</h5>
                <p class="text-muted">Create your first publisher to start managing journals and LOA requests.</p>
                <a href="{{ route('publisher.publishers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Publisher
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deletePublisher(publisherId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone! All related data will be deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/publisher/publishers/${publisherId}`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
