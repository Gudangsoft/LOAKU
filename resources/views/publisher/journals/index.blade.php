@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book me-2"></i>Journal Management</h2>
    <div class="d-flex gap-2">
        <!-- Export/Import Buttons -->
        <div class="btn-group me-2" role="group">
            <a href="{{ route('publisher.journals.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i>
                Export Excel
            </a>
            <a href="{{ route('publisher.journals.import.form') }}" class="btn btn-info btn-sm">
                <i class="fas fa-upload me-1"></i>
                Import Excel
            </a>
            <a href="{{ route('publisher.journals.template') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-file-excel me-1"></i>
                Template
            </a>
        </div>
        <a href="{{ route('publisher.journals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Journal
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">My Journals</h5>
    </div>
    <div class="card-body">
        @if($journals->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Journal Name</th>
                            <th>Publisher</th>
                            <th>ISSN</th>
                            <th>Chief Editor</th>
                            <th>LOA Requests</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($journals as $journal)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($journal->logo)
                                        <img src="{{ asset('storage/' . $journal->logo) }}" 
                                             class="me-2 rounded" width="32" height="32">
                                    @else
                                        <div class="bg-success text-white rounded d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($journal->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $journal->name }}</strong>
                                        @if($journal->website)
                                            <br><small><a href="{{ $journal->website }}" target="_blank" class="text-primary">{{ $journal->website }}</a></small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $journal->publisher->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($journal->e_issn)
                                    <div><small><strong>E-ISSN:</strong> {{ $journal->e_issn }}</small></div>
                                @endif
                                @if($journal->p_issn)
                                    <div><small><strong>P-ISSN:</strong> {{ $journal->p_issn }}</small></div>
                                @endif
                                @if(!$journal->e_issn && !$journal->p_issn)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $journal->chief_editor }}</td>
                            <td>
                                @php
                                    $total = $journal->loaRequests()->count();
                                    $pending = $journal->loaRequests()->where('status', 'pending')->count();
                                @endphp
                                <span class="badge bg-info">{{ $total }}</span>
                                @if($pending > 0)
                                    <span class="badge bg-warning">{{ $pending }} pending</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $journal->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('publisher.journals.edit', $journal) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="{{ route('publisher.journals.show', $journal) }}"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                        <li><a class="dropdown-item" href="{{ route('publisher.loa-requests.index') }}?journal={{ $journal->id }}"><i class="fas fa-file-alt me-2"></i>LOA Requests</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteJournal({{ $journal->id }})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $journals->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Journals Yet</h5>
                <p class="text-muted">Create your first journal to start receiving LOA requests.</p>
                <a href="{{ route('publisher.journals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Journal
                </a>
            </div>
        @endif
    </div>
</div>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteJournal(journalId) {
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
            const form = document.getElementById('deleteForm');
            form.action = `/publisher/journals/${journalId}`;
            form.submit();
        }
    });
}
</script>
@endsection
