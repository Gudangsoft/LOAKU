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
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('publisher.publishers.show', $publisher) }}"
                                       class="btn btn-sm" title="Detail"
                                       style="background:#EEF2FF;color:#4F46E5;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px">
                                        <i class="fas fa-eye" style="font-size:.75rem"></i>
                                    </a>
                                    <a href="{{ route('publisher.publishers.edit', $publisher) }}"
                                       class="btn btn-sm" title="Edit"
                                       style="background:#FEF3C7;color:#D97706;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px">
                                        <i class="fas fa-edit" style="font-size:.75rem"></i>
                                    </a>
                                    <button type="button"
                                            onclick="deletePublisher({{ $publisher->id }})"
                                            class="btn btn-sm" title="Hapus"
                                            style="background:#FEE2E2;color:#DC2626;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px">
                                        <i class="fas fa-trash" style="font-size:.75rem"></i>
                                    </button>
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
    if (!confirm('Hapus publisher ini? Tindakan tidak dapat dibatalkan.')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/publisher/publishers/${publisherId}`;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
