@extends('publisher.layout')

@section('title', 'Manajemen Jurnal')

@push('styles')
<style>
:root {
    --brand: #4F46E5;
    --brand-light: #EEF2FF;
    --brand-dark: #3730A3;
    --success: #10B981;
    --warning: #F59E0B;
    --danger: #EF4444;
    --info: #06B6D4;
    --surface: #FFFFFF;
    --bg: #F1F5F9;
    --border: #E2E8F0;
    --text: #1E293B;
    --muted: #64748B;
}

/* ── Page header ── */
.page-header {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.75rem;
}
.page-header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    background: rgba(255,255,255,.07);
    border-radius: 50%;
}
.page-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 250px; height: 250px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.page-header h1 { font-size: 1.6rem; font-weight: 700; margin: 0; }
.page-header p  { opacity: .8; margin: .25rem 0 0; font-size: .9rem; }

/* ── Stat chips ── */
.stat-chip {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 10px;
    padding: .6rem 1.1rem;
    text-align: center;
    backdrop-filter: blur(4px);
    min-width: 90px;
}
.stat-chip .val { font-size: 1.5rem; font-weight: 700; line-height: 1; display: block; }
.stat-chip .lbl { font-size: .7rem; opacity: .8; text-transform: uppercase; letter-spacing: .5px; }

/* ── Toolbar ── */
.toolbar {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    margin-bottom: 1.5rem;
    display: flex;
    gap: .75rem;
    align-items: center;
    flex-wrap: wrap;
}
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-wrap i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .85rem; }
.search-wrap input { padding-left: 2.3rem; border-radius: 9px; border: 1.5px solid var(--border); width: 100%; height: 38px; font-size: .875rem; }
.search-wrap input:focus { border-color: var(--brand); outline: none; box-shadow: 0 0 0 3px rgba(79,70,229,.12); }

.view-toggle .btn { padding: .4rem .65rem; border-color: var(--border); }
.view-toggle .btn.active { background: var(--brand); color: white; border-color: var(--brand); }

/* ── Journal card ── */
.journal-card {
    background: white;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    transition: all .22s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1.5px solid transparent;
}
.journal-card:hover {
    box-shadow: 0 8px 28px rgba(79,70,229,.15);
    border-color: var(--brand);
    transform: translateY(-3px);
}
.journal-card-top {
    padding: 1.25rem 1.25rem .9rem;
    display: flex;
    gap: .9rem;
    align-items: flex-start;
}
.j-avatar {
    width: 52px; height: 52px;
    border-radius: 12px;
    flex-shrink: 0;
    object-fit: cover;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 700; color: white;
}
.j-avatar img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; }
.j-meta { flex: 1; min-width: 0; }
.j-name {
    font-size: .95rem; font-weight: 700; color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin: 0 0 .15rem;
}
.j-publisher { font-size: .775rem; color: var(--muted); margin: 0; }

.badge-accred {
    font-size: .68rem; padding: .22rem .55rem;
    border-radius: 20px; font-weight: 600;
    white-space: nowrap;
}
.accred-s1 { background: #FEF3C7; color: #92400E; }
.accred-s2 { background: #DBEAFE; color: #1D4ED8; }
.accred-s3 { background: #D1FAE5; color: #065F46; }
.accred-s4 { background: #EDE9FE; color: #5B21B6; }
.accred-s5 { background: #FEE2E2; color: #991B1B; }
.accred-s6 { background: #F3F4F6; color: #374151; }
.accred-none { background: #F1F5F9; color: #94A3B8; }

/* ── Info grid inside card ── */
.journal-card-body { padding: 0 1.25rem .75rem; flex: 1; }
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .3rem .5rem;
}
.info-item { font-size: .775rem; }
.info-item .lbl { color: var(--muted); }
.info-item .val { color: var(--text); font-weight: 500; }

/* ── LOA stats bar ── */
.loa-bar {
    margin: .75rem 1.25rem;
    background: #F8FAFC;
    border-radius: 10px;
    padding: .7rem 1rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .25rem;
    text-align: center;
    border: 1px solid var(--border);
}
.loa-bar .lval { font-size: 1.25rem; font-weight: 700; line-height: 1; }
.loa-bar .llbl { font-size: .68rem; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }
.loa-bar .total .lval { color: var(--brand); }
.loa-bar .pending .lval { color: var(--warning); }
.loa-bar .approved .lval { color: var(--success); }

/* ── Card footer actions ── */
.journal-card-footer {
    padding: .75rem 1.25rem;
    border-top: 1px solid var(--border);
    display: flex;
    gap: .5rem;
    background: #FAFAFA;
}
.journal-card-footer .btn-action {
    flex: 1;
    font-size: .775rem;
    padding: .38rem .4rem;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: white;
    color: var(--text);
    transition: all .18s;
    display: flex; align-items: center; justify-content: center; gap: .3rem;
    cursor: pointer;
}
.journal-card-footer .btn-action:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
.journal-card-footer .btn-action.danger:hover { border-color: var(--danger); color: var(--danger); background: #FEF2F2; }

/* ── Table view ── */
#tableView table { font-size: .875rem; }
#tableView th { background: #F8FAFC; font-weight: 600; color: var(--muted); font-size: .775rem; text-transform: uppercase; letter-spacing: .5px; }
#tableView td { vertical-align: middle; }

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 4rem 1rem;
}
.empty-state .icon-wrap {
    width: 80px; height: 80px;
    background: var(--brand-light);
    border-radius: 20px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--brand);
    margin-bottom: 1.25rem;
}
.empty-state h4 { color: var(--text); font-weight: 700; margin-bottom: .5rem; }
.empty-state p  { color: var(--muted); margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div style="z-index:1;position:relative;">
            <h1><i class="fas fa-book-open me-2" style="opacity:.85"></i>Manajemen Jurnal</h1>
            <p>Kelola semua jurnal dan pantau statistik LOA secara real-time</p>
        </div>
        <div class="d-flex gap-2 flex-wrap" style="z-index:1;position:relative;">
            <a href="{{ route('publisher.journals.template') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);">
                <i class="fas fa-file-csv me-1"></i>Template
            </a>
            <a href="{{ route('publisher.journals.import.form') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);">
                <i class="fas fa-upload me-1"></i>Import
            </a>
            <a href="{{ route('publisher.journals.export') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);">
                <i class="fas fa-download me-1"></i>Export
            </a>
            <a href="{{ route('publisher.journals.create') }}" class="btn btn-sm fw-semibold" style="background:white;color:var(--brand);">
                <i class="fas fa-plus me-1"></i>Tambah Jurnal
            </a>
        </div>
    </div>

    {{-- stat chips --}}
    <div class="d-flex gap-2 mt-3 flex-wrap" style="z-index:1;position:relative;">
        <div class="stat-chip">
            <span class="val">{{ $stats['total'] }}</span>
            <span class="lbl">Total Jurnal</span>
        </div>
        <div class="stat-chip">
            <span class="val">{{ $stats['total_loa'] }}</span>
            <span class="lbl">Total LOA</span>
        </div>
        <div class="stat-chip">
            <span class="val">{{ $stats['pending'] }}</span>
            <span class="lbl">Pending</span>
        </div>
        <div class="stat-chip">
            <span class="val">{{ $stats['approved'] }}</span>
            <span class="lbl">Disetujui</span>
        </div>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="toolbar">
    <form action="{{ route('publisher.journals.index') }}" method="GET" class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Cari nama jurnal, ISSN, editor…"
               value="{{ request('search') }}" id="searchInput">
    </form>

    <div class="view-toggle btn-group" role="group">
        <button type="button" class="btn btn-sm active" id="btnGrid" onclick="setView('grid')" title="Grid">
            <i class="fas fa-th-large"></i>
        </button>
        <button type="button" class="btn btn-sm" id="btnTable" onclick="setView('table')" title="Tabel">
            <i class="fas fa-list"></i>
        </button>
    </div>

    <span class="text-muted small ms-1">
        {{ $journals->total() }} jurnal{{ $journals->total() != 1 ? '' : '' }}
    </span>
</div>

{{-- ── Grid View ── --}}
<div id="gridView">
    @if($journals->count() > 0)
        <div class="row g-3">
            @foreach($journals as $journal)
            @php
                $accred = strtolower($journal->accreditation_level ?? '');
                $accredClass = 'accred-none';
                $accredLabel = $journal->accreditation_level ?: 'Belum Terakreditasi';
                if (str_contains($accred,'1')) $accredClass='accred-s1';
                elseif (str_contains($accred,'2')) $accredClass='accred-s2';
                elseif (str_contains($accred,'3')) $accredClass='accred-s3';
                elseif (str_contains($accred,'4')) $accredClass='accred-s4';
                elseif (str_contains($accred,'5')) $accredClass='accred-s5';
                elseif (str_contains($accred,'6')) $accredClass='accred-s6';

                $colors = ['#4F46E5','#7C3AED','#DB2777','#059669','#D97706','#0284C7','#DC2626'];
                $color = $colors[crc32($journal->name) % count($colors)];
            @endphp
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="journal-card">
                    {{-- top --}}
                    <div class="journal-card-top">
                        <div class="j-avatar" style="background: {{ $color }};">
                            @if($journal->logo)
                                <img src="{{ asset('storage/' . $journal->logo) }}" alt="">
                            @else
                                {{ strtoupper(substr($journal->name, 0, 2)) }}
                            @endif
                        </div>
                        <div class="j-meta">
                            <p class="j-name" title="{{ $journal->name }}">{{ $journal->name }}</p>
                            <p class="j-publisher">
                                <i class="fas fa-building" style="font-size:.7rem;margin-right:3px;"></i>
                                {{ $journal->publisher->name ?? 'Belum ada publisher' }}
                            </p>
                        </div>
                        <span class="badge-accred {{ $accredClass }} ms-auto flex-shrink-0">
                            {{ $accredLabel }}
                        </span>
                    </div>

                    {{-- info grid --}}
                    <div class="journal-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="lbl">P-ISSN</div>
                                <div class="val">{{ $journal->p_issn ?: '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">E-ISSN</div>
                                <div class="val">{{ $journal->e_issn ?: '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Editor</div>
                                <div class="val" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $journal->chief_editor }}">
                                    {{ $journal->chief_editor ?: '—' }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">SINTA ID</div>
                                <div class="val">{{ $journal->sinta_id ?: '—' }}</div>
                            </div>
                        </div>

                        @if($journal->website)
                        <div class="mt-2" style="font-size:.75rem;">
                            <a href="{{ $journal->website }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="fas fa-external-link-alt me-1" style="font-size:.7rem;"></i>{{ parse_url($journal->website, PHP_URL_HOST) ?: $journal->website }}
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- LOA stats --}}
                    <div class="loa-bar">
                        <div class="total">
                            <div class="lval">{{ $journal->loa_requests_count }}</div>
                            <div class="llbl">Total LOA</div>
                        </div>
                        <div class="pending">
                            <div class="lval">{{ $journal->pending_count }}</div>
                            <div class="llbl">Pending</div>
                        </div>
                        <div class="approved">
                            <div class="lval">{{ $journal->approved_count }}</div>
                            <div class="llbl">Disetujui</div>
                        </div>
                    </div>

                    {{-- actions --}}
                    <div class="journal-card-footer">
                        <a href="{{ route('publisher.journals.show', $journal) }}" class="btn-action" title="Detail">
                            <i class="fas fa-eye"></i><span>Detail</span>
                        </a>
                        <a href="{{ route('publisher.journals.edit', $journal) }}" class="btn-action" title="Edit">
                            <i class="fas fa-edit"></i><span>Edit</span>
                        </a>
                        <a href="{{ route('publisher.loa-requests.index') }}?journal={{ $journal->id }}" class="btn-action" title="LOA">
                            <i class="fas fa-file-alt"></i>
                            <span>LOA @if($journal->pending_count > 0)<span style="background:#F59E0B;color:white;border-radius:10px;padding:0 5px;font-size:.65rem;">{{ $journal->pending_count }}</span>@endif</span>
                        </a>
                        <button type="button" class="btn-action danger" onclick="deleteJournal({{ $journal->id }}, '{{ addslashes($journal->name) }}')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $journals->links() }}
        </div>

    @else
        <div class="empty-state">
            <div class="icon-wrap"><i class="fas fa-book-open"></i></div>
            @if(request('search'))
                <h4>Jurnal tidak ditemukan</h4>
                <p>Tidak ada jurnal yang cocok dengan "<strong>{{ request('search') }}</strong>".</p>
                <a href="{{ route('publisher.journals.index') }}" class="btn btn-outline-secondary me-2">Reset Pencarian</a>
            @else
                <h4>Belum ada jurnal</h4>
                <p>Tambahkan jurnal pertama Anda untuk mulai menerima pengajuan LOA.</p>
            @endif
            <a href="{{ route('publisher.journals.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Jurnal
            </a>
        </div>
    @endif
</div>

{{-- ── Table View ── --}}
<div id="tableView" style="display:none;">
    @if($journals->count() > 0)
    <div class="card" style="border-radius:14px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Jurnal</th>
                        <th>ISSN</th>
                        <th>Editor / SINTA</th>
                        <th>Akreditasi</th>
                        <th class="text-center">LOA</th>
                        <th class="text-center">Pending</th>
                        <th class="text-center">Disetujui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journals as $journal)
                    @php
                        $colors = ['#4F46E5','#7C3AED','#DB2777','#059669','#D97706','#0284C7','#DC2626'];
                        $color = $colors[crc32($journal->name) % count($colors)];
                        $accred = strtolower($journal->accreditation_level ?? '');
                        $accredClass = 'accred-none';
                        if (str_contains($accred,'1')) $accredClass='accred-s1';
                        elseif (str_contains($accred,'2')) $accredClass='accred-s2';
                        elseif (str_contains($accred,'3')) $accredClass='accred-s3';
                        elseif (str_contains($accred,'4')) $accredClass='accred-s4';
                        elseif (str_contains($accred,'5')) $accredClass='accred-s5';
                        elseif (str_contains($accred,'6')) $accredClass='accred-s6';
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="j-avatar" style="background:{{ $color }};width:36px;height:36px;border-radius:9px;font-size:.8rem;flex-shrink:0;">
                                    @if($journal->logo)
                                        <img src="{{ asset('storage/'.$journal->logo) }}" alt="" style="width:36px;height:36px;border-radius:9px;">
                                    @else
                                        {{ strtoupper(substr($journal->name,0,2)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:.875rem;">{{ $journal->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">{{ $journal->publisher->name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.8rem;">
                            @if($journal->p_issn)<div><span class="text-muted">P:</span> {{ $journal->p_issn }}</div>@endif
                            @if($journal->e_issn)<div><span class="text-muted">E:</span> {{ $journal->e_issn }}</div>@endif
                            @if(!$journal->p_issn && !$journal->e_issn)<span class="text-muted">—</span>@endif
                        </td>
                        <td style="font-size:.8rem;">
                            <div>{{ $journal->chief_editor ?: '—' }}</div>
                            @if($journal->sinta_id)<div class="text-muted">SINTA: {{ $journal->sinta_id }}</div>@endif
                        </td>
                        <td>
                            <span class="badge-accred {{ $accredClass }}">{{ $journal->accreditation_level ?: '—' }}</span>
                        </td>
                        <td class="text-center fw-bold" style="color:var(--brand);">{{ $journal->loa_requests_count }}</td>
                        <td class="text-center">
                            @if($journal->pending_count > 0)
                                <span class="badge bg-warning text-dark">{{ $journal->pending_count }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center" style="color:var(--success);font-weight:600;">{{ $journal->approved_count }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('publisher.journals.show', $journal) }}" class="btn btn-sm" style="background:#EEF2FF;color:#4F46E5;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;" title="Detail">
                                    <i class="fas fa-eye" style="font-size:.7rem"></i>
                                </a>
                                <a href="{{ route('publisher.journals.edit', $journal) }}" class="btn btn-sm" style="background:#FEF3C7;color:#D97706;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;" title="Edit">
                                    <i class="fas fa-edit" style="font-size:.7rem"></i>
                                </a>
                                <a href="{{ route('publisher.loa-requests.index') }}?journal={{ $journal->id }}" class="btn btn-sm" style="background:#ECFDF5;color:#059669;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;" title="LOA Requests">
                                    <i class="fas fa-file-alt" style="font-size:.7rem"></i>
                                </a>
                                <button type="button" onclick="deleteJournal({{ $journal->id }}, '{{ addslashes($journal->name) }}')" class="btn btn-sm" style="background:#FEE2E2;color:#DC2626;border:none;width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;" title="Hapus">
                                    <i class="fas fa-trash" style="font-size:.7rem"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $journals->links() }}
    </div>
    @endif
</div>

<form id="deleteForm" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
/* ── View toggle ── */
const VIEW_KEY = 'journals_view';
function setView(v) {
    localStorage.setItem(VIEW_KEY, v);
    document.getElementById('gridView').style.display  = v === 'grid'  ? '' : 'none';
    document.getElementById('tableView').style.display = v === 'table' ? '' : 'none';
    document.getElementById('btnGrid').classList.toggle('active',  v === 'grid');
    document.getElementById('btnTable').classList.toggle('active', v === 'table');
}
setView(localStorage.getItem(VIEW_KEY) || 'grid');

/* ── Delete ── */
function deleteJournal(id, name) {
    Swal.fire({
        title: 'Hapus jurnal ini?',
        html: `Jurnal <strong>${name}</strong> akan dihapus permanen beserta semua data terkait.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = `/publisher/journals/${id}`;
            form.submit();
        }
    });
}

/* ── Live search (debounce) ── */
let searchTimer;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    searchTimer = setTimeout(() => {
        const url = new URL(window.location.href);
        if (q) url.searchParams.set('search', q);
        else url.searchParams.delete('search');
        window.location.href = url.toString();
    }, 500);
});
</script>
@endpush
