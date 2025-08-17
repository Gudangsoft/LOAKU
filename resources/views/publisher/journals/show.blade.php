@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book me-2"></i>{{ $journal->name }}</h2>
    <div>
        <a href="{{ route('publisher.journals.edit', $journal) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-2"></i>Edit Journal
        </a>
        <a href="{{ route('publisher.journals.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Journals
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Journal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($journal->logo)
                            <img src="{{ asset('storage/' . $journal->logo) }}" 
                                 class="img-thumbnail" style="max-width: 100px;">
                        @else
                            <div class="bg-success text-white rounded d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px; font-size: 24px;">
                                {{ strtoupper(substr($journal->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Journal Name:</strong></td>
                                <td>{{ $journal->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Publisher:</strong></td>
                                <td>{{ $journal->publisher->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Chief Editor:</strong></td>
                                <td>{{ $journal->chief_editor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Contact Email:</strong></td>
                                <td><a href="mailto:{{ $journal->email }}">{{ $journal->email }}</a></td>
                            </tr>
                            @if($journal->website)
                            <tr>
                                <td><strong>Website:</strong></td>
                                <td><a href="{{ $journal->website }}" target="_blank">{{ $journal->website }}</a></td>
                            </tr>
                            @endif
                            @if($journal->e_issn || $journal->p_issn)
                            <tr>
                                <td><strong>ISSN:</strong></td>
                                <td>
                                    @if($journal->e_issn)
                                        <span class="badge bg-info me-2">E-ISSN: {{ $journal->e_issn }}</span>
                                    @endif
                                    @if($journal->p_issn)
                                        <span class="badge bg-secondary">P-ISSN: {{ $journal->p_issn }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $journal->created_at->format('d F Y \a\t H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $journal->updated_at->format('d F Y \a\t H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($journal->description)
                <hr>
                <div>
                    <h6><strong>Description:</strong></h6>
                    <p class="text-muted">{{ $journal->description }}</p>
                </div>
                @endif

                @if($journal->signature_stamp)
                <hr>
                <div>
                    <h6><strong>Signature Stamp:</strong></h6>
                    <img src="{{ asset('storage/' . $journal->signature_stamp) }}" 
                         class="img-thumbnail" style="max-height: 150px;">
                    <div class="form-text">Used for LOA certificates</div>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent LOA Requests</h5>
                <a href="{{ route('publisher.loa-requests.index') }}?journal={{ $journal->id }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @php
                    $recentRequests = $journal->loaRequests()->latest()->limit(5)->get();
                @endphp

                @if($recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Article Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>
                                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $request->article_title }}
                                        </div>
                                    </td>
                                    <td>{{ $request->corresponding_author }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->created_at->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">No LOA Requests Yet</h6>
                        <p class="text-muted">LOA requests for this journal will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                @php
                    $totalRequests = $journal->loaRequests()->count();
                    $pendingRequests = $journal->loaRequests()->where('status', 'pending')->count();
                    $approvedRequests = $journal->loaRequests()->where('status', 'approved')->count();
                    $rejectedRequests = $journal->loaRequests()->where('status', 'rejected')->count();
                    $approvalRate = $totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100, 1) : 0;
                @endphp

                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <h3 class="text-primary mb-0">{{ $totalRequests }}</h3>
                            <small class="text-muted">Total Requests</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <h3 class="text-success mb-0">{{ $approvalRate }}%</h3>
                            <small class="text-muted">Approval Rate</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Pending</span>
                        <span class="badge bg-warning">{{ $pendingRequests }}</span>
                    </div>
                    @if($totalRequests > 0)
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: {{ ($pendingRequests / $totalRequests) * 100 }}%"></div>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Approved</span>
                        <span class="badge bg-success">{{ $approvedRequests }}</span>
                    </div>
                    @if($totalRequests > 0)
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ ($approvedRequests / $totalRequests) * 100 }}%"></div>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Rejected</span>
                        <span class="badge bg-danger">{{ $rejectedRequests }}</span>
                    </div>
                    @if($totalRequests > 0)
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-danger" style="width: {{ ($rejectedRequests / $totalRequests) * 100 }}%"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('publisher.journals.edit', $journal) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Journal
                    </a>
                    <a href="{{ route('publisher.loa-requests.index') }}?journal={{ $journal->id }}" class="btn btn-outline-info">
                        <i class="fas fa-file-alt me-2"></i>View LOA Requests
                    </a>
                    @if($journal->website)
                        <a href="{{ $journal->website }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-2"></i>Visit Website
                        </a>
                    @endif
                    <button type="button" class="btn btn-outline-danger" onclick="deleteJournal({{ $journal->id }})">
                        <i class="fas fa-trash me-2"></i>Delete Journal
                    </button>
                </div>
            </div>
        </div>

        @if($journal->loaRequests()->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Monthly Activity</h5>
            </div>
            <div class="card-body">
                @php
                    $monthlyData = $journal->loaRequests()
                        ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                        ->whereYear('created_at', date('Y'))
                        ->groupByRaw('MONTH(created_at)')
                        ->orderByRaw('MONTH(created_at)')
                        ->get();
                @endphp

                @if($monthlyData->count() > 0)
                    @foreach($monthlyData as $data)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ date('F', mktime(0, 0, 0, $data->month, 1)) }}</span>
                            <span class="badge bg-primary">{{ $data->count }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No activity this year</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<form id="deleteForm" action="{{ route('publisher.journals.destroy', $journal) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteJournal(journalId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone! All related LOA requests and data will be deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endsection
