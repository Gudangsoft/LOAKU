@extends('layouts.admin')

@section('title', 'Publishers')
@section('subtitle', 'Manage publisher information')

@push('styles')
<style>
    .stat-card {
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid transparent;
        transition: box-shadow .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .stat-val  { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .stat-lbl  { font-size: .75rem; color: #6B7280; margin-top: 2px; }

    .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .filter-tab {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid #E5E7EB;
        color: #6B7280;
        background: white;
        transition: all .15s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .filter-tab:hover { border-color: #6366F1; color: #6366F1; }
    .filter-tab.active { background: #6366F1; border-color: #6366F1; color: white; }
    .filter-tab .cnt {
        background: rgba(255,255,255,.25);
        border-radius: 10px;
        padding: 1px 6px;
        font-size: .7rem;
    }
    .filter-tab:not(.active) .cnt { background: #F3F4F6; color: #374151; }

    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: .75rem; font-weight: 600;
    }
    .badge-status .dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .badge-active   { background: #DCFCE7; color: #16A34A; }
    .badge-active .dot   { background: #16A34A; }
    .badge-pending  { background: #FEF3C7; color: #D97706; }
    .badge-pending .dot  { background: #D97706; }
    .badge-suspended{ background: #FEE2E2; color: #DC2626; }
    .badge-suspended .dot{ background: #DC2626; }

    .publisher-logo {
        width: 44px; height: 44px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        background: #F9FAFB;
        padding: 3px;
    }
    .publisher-logo-placeholder {
        width: 44px; height: 44px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366F1, #06B6D4);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: .9rem; font-weight: 700;
        flex-shrink: 0;
    }

    .contact-icon {
        width: 26px; height: 26px;
        border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .7rem;
        text-decoration: none;
        transition: all .15s;
    }
    .contact-icon.phone { background: #ECFDF5; color: #059669; }
    .contact-icon.wa    { background: #DCFCE7; color: #16A34A; }
    .contact-icon.web   { background: #DBEAFE; color: #2563EB; }
    .contact-icon:hover { filter: brightness(.9); transform: scale(1.1); }

    .action-btn {
        width: 30px; height: 30px;
        border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .75rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .15s;
    }
    .action-btn:hover { transform: translateY(-1px); }
    .action-btn.view    { background: #EEF2FF; color: #4F46E5; }
    .action-btn.edit    { background: #FEF3C7; color: #D97706; }
    .action-btn.del     { background: #FEE2E2; color: #DC2626; }
    .action-btn.activate{ background: #DCFCE7; color: #16A34A; }
    .action-btn.suspend { background: #FEE2E2; color: #DC2626; }
    .action-btn:disabled{ opacity: .45; cursor: not-allowed; transform: none !important; }

    .search-input-wrap { position: relative; }
    .search-input-wrap .si {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%); color: #9CA3AF; font-size: .85rem;
        pointer-events: none;
    }
    .search-input-wrap input {
        padding-left: 34px;
        border-radius: 10px;
        border: 1.5px solid #E5E7EB;
        font-size: .875rem;
        height: 36px;
        transition: border-color .2s;
    }
    .search-input-wrap input:focus { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); outline: none; }

    .empty-state { text-align: center; padding: 60px 0; color: #6B7280; }
    .empty-state i { font-size: 3rem; color: #D1D5DB; margin-bottom: 12px; display: block; }

    .modal-backdrop.show { opacity: .45; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius:12px;border:none;background:#DCFCE7;color:#166534">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius:12px;border:none;background:#FEE2E2;color:#991B1B">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1 fw-bold" style="color:#111827"><i class="fas fa-building me-2" style="color:#6366F1"></i>Data Publisher</h1>
            <p class="mb-0 text-muted" style="font-size:.875rem">Kelola data penerbit/institusi dalam sistem</p>
        </div>
        <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary btn-sm px-3" style="border-radius:10px;font-weight:600">
            <i class="fas fa-plus me-1"></i> Tambah Publisher
        </a>
    </div>

    {{-- Stats Bar --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#F0F9FF;border-color:#BAE6FD">
                <div class="stat-icon" style="background:#DBEAFE;color:#2563EB"><i class="fas fa-building"></i></div>
                <div>
                    <div class="stat-val" style="color:#1D4ED8">{{ $stats['total'] }}</div>
                    <div class="stat-lbl">Total Publisher</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#F0FDF4;border-color:#BBF7D0">
                <div class="stat-icon" style="background:#DCFCE7;color:#16A34A"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-val" style="color:#15803D">{{ $stats['active'] }}</div>
                    <div class="stat-lbl">Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#FFFBEB;border-color:#FDE68A">
                <div class="stat-icon" style="background:#FEF3C7;color:#D97706"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-val" style="color:#B45309">{{ $stats['pending'] }}</div>
                    <div class="stat-lbl">Menunggu Validasi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:#FFF1F2;border-color:#FECDD3">
                <div class="stat-icon" style="background:#FEE2E2;color:#DC2626"><i class="fas fa-ban"></i></div>
                <div>
                    <div class="stat-val" style="color:#B91C1C">{{ $stats['suspended'] }}</div>
                    <div class="stat-lbl">Disuspend</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card" style="border-radius:16px;border:1px solid #E5E7EB;box-shadow:0 2px 12px rgba(0,0,0,.05)">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-4" style="background:white;border-bottom:1px solid #F3F4F6;border-radius:16px 16px 0 0">
            {{-- Filter Tabs --}}
            <div class="filter-tabs">
                <a href="{{ route('admin.publishers.index', array_merge(request()->except('status','page'), [])) }}"
                   class="filter-tab {{ !request('status') ? 'active' : '' }}">
                    Semua <span class="cnt">{{ $stats['total'] }}</span>
                </a>
                <a href="{{ route('admin.publishers.index', array_merge(request()->except('status','page'), ['status'=>'pending'])) }}"
                   class="filter-tab {{ request('status')=='pending' ? 'active' : '' }}">
                    <span class="dot" style="width:6px;height:6px;border-radius:50%;background:#D97706;display:inline-block"></span>
                    Pending <span class="cnt">{{ $stats['pending'] }}</span>
                </a>
                <a href="{{ route('admin.publishers.index', array_merge(request()->except('status','page'), ['status'=>'active'])) }}"
                   class="filter-tab {{ request('status')=='active' ? 'active' : '' }}">
                    <span class="dot" style="width:6px;height:6px;border-radius:50%;background:#16A34A;display:inline-block"></span>
                    Aktif <span class="cnt">{{ $stats['active'] }}</span>
                </a>
                <a href="{{ route('admin.publishers.index', array_merge(request()->except('status','page'), ['status'=>'suspended'])) }}"
                   class="filter-tab {{ request('status')=='suspended' ? 'active' : '' }}">
                    <span class="dot" style="width:6px;height:6px;border-radius:50%;background:#DC2626;display:inline-block"></span>
                    Suspended <span class="cnt">{{ $stats['suspended'] }}</span>
                </a>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.publishers.index') }}" class="d-flex gap-2 align-items-center">
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                <div class="search-input-wrap">
                    <i class="fas fa-search si"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email..." style="width:220px">
                </div>
                @if(request('q') || request('status'))
                <a href="{{ route('admin.publishers.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;height:36px;display:flex;align-items:center" title="Reset filter">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            @if($publishers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.875rem">
                    <thead style="background:#F9FAFB;border-bottom:1px solid #E5E7EB">
                        <tr>
                            <th class="ps-4" style="width:40px;color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">#</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">PUBLISHER</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">ALAMAT</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">KONTAK</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">STATUS</th>
                            <th style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">JURNAL</th>
                            <th class="pe-4 text-end" style="color:#6B7280;font-size:.75rem;font-weight:700;padding:12px 8px">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publishers as $index => $publisher)
                        <tr style="border-bottom:1px solid #F3F4F6">
                            <td class="ps-4" style="vertical-align:middle;color:#9CA3AF;font-size:.8rem;padding:14px 8px">
                                {{ $publishers->firstItem() + $index }}
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                <div class="d-flex align-items-center gap-2">
                                    @if($publisher->logo)
                                        <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="publisher-logo">
                                    @else
                                        <div class="publisher-logo-placeholder">{{ strtoupper(substr($publisher->name,0,1)) }}</div>
                                    @endif
                                    <div>
                                        <div class="fw-600" style="color:#111827;font-weight:600">{{ $publisher->name }}</div>
                                        <div style="font-size:.78rem;color:#6B7280">{{ $publisher->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px;max-width:180px">
                                <div class="text-truncate" style="color:#374151;font-size:.83rem" title="{{ $publisher->address }}">
                                    {{ $publisher->address ?? '-' }}
                                </div>
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                <div class="d-flex gap-1 flex-wrap">
                                    @if($publisher->phone)
                                    <a href="tel:{{ $publisher->phone }}" class="contact-icon phone" title="{{ $publisher->phone }}">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    @endif
                                    @if($publisher->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$publisher->whatsapp) }}" target="_blank" class="contact-icon wa" title="WA: {{ $publisher->whatsapp }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    @endif
                                    @if($publisher->website)
                                    <a href="{{ $publisher->website }}" target="_blank" class="contact-icon web" title="{{ $publisher->website }}">
                                        <i class="fas fa-globe"></i>
                                    </a>
                                    @endif
                                    @if(!$publisher->phone && !$publisher->whatsapp && !$publisher->website)
                                        <span style="color:#D1D5DB;font-size:.8rem">—</span>
                                    @endif
                                </div>
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                @if($publisher->status === 'active')
                                    <span class="badge-status badge-active"><span class="dot"></span>Aktif</span>
                                @elseif($publisher->status === 'pending')
                                    <span class="badge-status badge-pending"><span class="dot"></span>Pending</span>
                                @elseif($publisher->status === 'suspended')
                                    <span class="badge-status badge-suspended"><span class="dot"></span>Suspended</span>
                                @else
                                    <span style="color:#9CA3AF;font-size:.8rem">{{ $publisher->status }}</span>
                                @endif
                            </td>
                            <td style="vertical-align:middle;padding:14px 8px">
                                <span style="background:#EEF2FF;color:#4F46E5;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600">
                                    {{ $publisher->journals_count }}
                                </span>
                            </td>
                            <td class="pe-4 text-end" style="vertical-align:middle;padding:14px 8px">
                                <div class="d-flex justify-content-end gap-1">
                                    {{-- View --}}
                                    <a href="{{ route('admin.publishers.show', $publisher) }}" class="action-btn view" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.publishers.edit', $publisher) }}" class="action-btn edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- Quick Status Toggle --}}
                                    @if($publisher->status !== 'active')
                                    <button type="button"
                                            class="action-btn activate"
                                            title="Aktifkan publisher"
                                            onclick="confirmToggle({{ $publisher->id }}, '{{ addslashes($publisher->name) }}', 'activate')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                    @if($publisher->status !== 'suspended')
                                    <button type="button"
                                            class="action-btn suspend"
                                            title="Suspend publisher"
                                            onclick="confirmToggle({{ $publisher->id }}, '{{ addslashes($publisher->name) }}', 'suspend')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                    {{-- Delete --}}
                                    @if($publisher->journals_count == 0)
                                    <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus publisher ini? Tindakan tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn del" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="action-btn del" disabled
                                            title="Tidak dapat dihapus — publisher memiliki {{ $publisher->journals_count }} jurnal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-top:1px solid #F3F4F6">
                <div style="font-size:.8rem;color:#6B7280">
                    Menampilkan {{ $publishers->firstItem() }}–{{ $publishers->lastItem() }} dari {{ $publishers->total() }} publisher
                </div>
                {{ $publishers->links() }}
            </div>

            @else
            <div class="empty-state">
                <i class="fas fa-building"></i>
                <h6 class="fw-600" style="color:#374151">
                    @if(request('q') || request('status'))
                        Tidak ada publisher yang cocok
                    @else
                        Belum ada data publisher
                    @endif
                </h6>
                <p style="font-size:.875rem">
                    @if(request('q') || request('status'))
                        Coba ubah filter atau kata kunci pencarian.
                        <a href="{{ route('admin.publishers.index') }}">Reset filter</a>
                    @else
                        Klik <strong>Tambah Publisher</strong> untuk menambahkan penerbit baru.
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Toggle Status Modal --}}
<div class="modal fade" id="toggleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h6 class="modal-title fw-bold" id="toggleModalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="toggleForm" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="action" id="toggleAction">
                <div class="modal-body px-4 py-3">
                    <p id="toggleModalDesc" style="font-size:.875rem;color:#374151;margin-bottom:14px"></p>
                    <div id="notesWrap">
                        <label style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:4px" id="notesLabel">Catatan</label>
                        <textarea name="notes" rows="3" class="form-control" style="font-size:.875rem;border-radius:10px;resize:none" placeholder="Opsional..." id="notesField"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal" style="border-radius:8px">Batal</button>
                    <button type="submit" class="btn btn-sm px-4" id="toggleSubmitBtn" style="border-radius:8px;font-weight:600"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmToggle(id, name, action) {
    const modal     = document.getElementById('toggleModal');
    const form      = document.getElementById('toggleForm');
    const titleEl   = document.getElementById('toggleModalTitle');
    const descEl    = document.getElementById('toggleModalDesc');
    const actionInp = document.getElementById('toggleAction');
    const submitBtn = document.getElementById('toggleSubmitBtn');
    const notesField= document.getElementById('notesField');
    const notesLabel= document.getElementById('notesLabel');

    const baseUrl = "{{ url('admin/publishers') }}";
    form.action   = `${baseUrl}/${id}/toggle-status`;
    actionInp.value = action;

    if (action === 'activate') {
        titleEl.textContent   = 'Aktifkan Publisher';
        descEl.textContent    = `Aktifkan publisher "${name}"? Publisher akan muncul di halaman publik.`;
        notesLabel.textContent = 'Catatan aktivasi (opsional)';
        notesField.placeholder = 'Mis: Dokumen telah diverifikasi...';
        notesField.required    = false;
        submitBtn.className    = 'btn btn-success btn-sm px-4';
        submitBtn.textContent  = 'Aktifkan';
    } else {
        titleEl.textContent   = 'Suspend Publisher';
        descEl.textContent    = `Suspend publisher "${name}"? Publisher tidak akan tampil di halaman publik.`;
        notesLabel.textContent = 'Alasan suspend';
        notesField.placeholder = 'Tuliskan alasan suspend...';
        notesField.required    = false;
        submitBtn.className    = 'btn btn-danger btn-sm px-4';
        submitBtn.textContent  = 'Suspend';
    }

    notesField.value = '';
    new bootstrap.Modal(modal).show();
}
</script>
@endpush
