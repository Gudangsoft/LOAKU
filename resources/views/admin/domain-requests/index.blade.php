@extends('layouts.admin')

@section('title', 'Permintaan Domain Kustom')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-globe me-2 text-primary"></i>Permintaan Domain Kustom
            @if($pendingCount > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }} pending</span>
            @endif
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / domain..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.domain-requests.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Publisher</th>
                            <th>Domain yang Diminta</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($publishers as $pub)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $pub->name }}</div>
                                <div class="text-muted small">{{ $pub->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if($pub->subdomain)
                                    <code>{{ config('app.base_domain') }}/{{ $pub->subdomain }}</code>
                                @elseif($pub->custom_domain)
                                    <code>{{ $pub->custom_domain }}</code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($pub->subdomain)
                                    <span class="badge bg-primary-subtle text-primary">Subdomain</span>
                                @elseif($pub->custom_domain)
                                    <span class="badge bg-success-subtle text-success">Domain Kustom</span>
                                @endif
                            </td>
                            <td>
                                @if($pub->domain_status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                @elseif($pub->domain_status === 'active')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>
                                @elseif($pub->domain_status === 'rejected')
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $pub->updated_at->format('d M Y') }}</div>
                                <div class="text-muted" style="font-size:.75rem;">{{ $pub->updated_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-center pe-4">
                                @if($pub->domain_status === 'pending')
                                {{-- Approve --}}
                                <button type="button" class="btn btn-sm btn-success me-1"
                                        data-bs-toggle="modal" data-bs-target="#approveModal{{ $pub->id }}"
                                        title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                {{-- Reject --}}
                                <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pub->id }}"
                                        title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>

                                @elseif($pub->domain_status === 'active')
                                @if($pub->getPublicDomainUrl())
                                <a href="{{ $pub->getPublicDomainUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Buka Portal">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.domain-requests.revoke', $pub) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Cabut domain {{ $pub->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cabut Domain">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>

                                @elseif($pub->domain_status === 'rejected')
                                <span class="text-muted small">{{ $pub->domain_notes }}</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Approve Modal --}}
                        @if($pub->domain_status === 'pending')
                        <div class="modal fade" id="approveModal{{ $pub->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.domain-requests.approve', $pub) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h6 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i>Setujui Domain</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Setujui domain untuk <strong>{{ $pub->name }}</strong>?</p>
                                            <p class="text-muted small">URL: <code>{{ $pub->subdomain ? config('app.base_domain').'/'.$pub->subdomain : $pub->custom_domain }}</code></p>
                                            @if($pub->custom_domain)
                                            <div class="alert alert-info small">
                                                Pastikan DNS sudah diarahkan ke server ini sebelum mengaktifkan domain kustom.
                                            </div>
                                            @endif
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Catatan (opsional)</label>
                                                <textarea name="notes" rows="2" class="form-control" placeholder="Catatan untuk publisher..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Aktifkan Domain</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Reject Modal --}}
                        <div class="modal fade" id="rejectModal{{ $pub->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.domain-requests.reject', $pub) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title fw-bold"><i class="fas fa-times-circle me-2"></i>Tolak Permintaan Domain</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tolak permintaan domain dari <strong>{{ $pub->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="notes" rows="3" class="form-control" required
                                                          placeholder="Jelaskan alasan penolakan..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-times me-1"></i>Tolak</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-globe fa-2x mb-2 d-block"></i>
                                Belum ada permintaan domain kustom.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($publishers->hasPages())
        <div class="card-footer">{{ $publishers->links() }}</div>
        @endif
    </div>
</div>
@endsection
