@extends('layouts.member')

@section('title', 'Tracking LOA Saya')

@section('content')
<div style="padding:24px">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px">
        <div>
            <h4 style="font-weight:800;color:#1E1B4B;margin:0">Tracking LOA Saya</h4>
            <p style="color:#64748B;margin:4px 0 0;font-size:.875rem">Pantau status seluruh pengajuan LOA Anda secara real-time</p>
        </div>
        <a href="{{ route('loa.create') }}"
           style="background:linear-gradient(135deg,#4F46E5,#06B6D4);color:white;padding:10px 20px;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none">
            <i class="fas fa-plus me-2"></i>Ajukan LOA Baru
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label'=>'Total Pengajuan', 'value'=>$stats['total'],    'icon'=>'fa-file-alt',      'bg'=>'#EEF2FF','color'=>'#4F46E5'],
                ['label'=>'Menunggu Review', 'value'=>$stats['pending'],  'icon'=>'fa-hourglass-half','bg'=>'#FEF9C3','color'=>'#CA8A04'],
                ['label'=>'Disetujui',       'value'=>$stats['approved'], 'icon'=>'fa-check-circle',  'bg'=>'#DCFCE7','color'=>'#16A34A'],
                ['label'=>'Ditolak',         'value'=>$stats['rejected'], 'icon'=>'fa-times-circle',  'bg'=>'#FEE2E2','color'=>'#DC2626'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="col-6 col-md-3">
            <div style="background:white;border-radius:14px;border:1px solid #E2E8F0;padding:18px 20px;display:flex;align-items:center;gap:14px">
                <div style="width:46px;height:46px;background:{{ $card['bg'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas {{ $card['icon'] }}" style="color:{{ $card['color'] }};font-size:1.1rem"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#1E1B4B;line-height:1">{{ $card['value'] }}</div>
                    <div style="font-size:.78rem;color:#64748B;margin-top:2px">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div style="background:white;border-radius:14px;border:1px solid #E2E8F0;padding:14px 18px;margin-bottom:16px">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm"
                       placeholder="Cari judul artikel atau nama penulis...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status')==='approved' ? 'selected':'' }}>Disetujui</option>
                    <option value="rejected" {{ request('status')==='rejected' ? 'selected':'' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
                @if(request()->hasAny(['q','status']))
                <a href="{{ route('member.requests') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- List --}}
    @forelse($requests as $req)
    @php
        $statusMap = [
            'pending'  => ['label'=>'Menunggu Review','bg'=>'#FEF9C3','color'=>'#92400E','icon'=>'fa-hourglass-half','dot'=>'#EAB308'],
            'approved' => ['label'=>'Disetujui',      'bg'=>'#DCFCE7','color'=>'#166534','icon'=>'fa-check-circle',  'dot'=>'#22C55E'],
            'rejected' => ['label'=>'Ditolak',        'bg'=>'#FEE2E2','color'=>'#991B1B','icon'=>'fa-times-circle',  'dot'=>'#EF4444'],
        ];
        $s = $statusMap[$req->status] ?? $statusMap['pending'];
    @endphp
    <div style="background:white;border-radius:14px;border:1px solid #E2E8F0;margin-bottom:12px;overflow:hidden">
        {{-- Top bar status color --}}
        <div style="height:3px;background:{{ $s['dot'] }}"></div>

        <div style="padding:18px 20px">
            <div class="row g-3 align-items-start">
                {{-- Info utama --}}
                <div class="col-md-7">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                        <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700">
                            <i class="fas {{ $s['icon'] }} me-1"></i>{{ $s['label'] }}
                        </span>
                        <span style="font-size:.78rem;color:#94A3B8">{{ $req->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <h6 style="font-weight:700;color:#1E1B4B;margin:0 0 4px;line-height:1.4">{{ $req->article_title }}</h6>
                    <div style="font-size:.82rem;color:#64748B">
                        <i class="fas fa-user me-1"></i>{{ $req->author }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-envelope me-1"></i>{{ $req->author_email }}
                    </div>
                    <div style="font-size:.82rem;color:#64748B;margin-top:4px">
                        <i class="fas fa-book me-1"></i><strong>{{ $req->journal->name ?? '-' }}</strong>
                        @if($req->journal?->publisher)
                        &nbsp;·&nbsp;{{ $req->journal->publisher->name }}
                        @endif
                    </div>
                    <div style="font-size:.78rem;color:#94A3B8;margin-top:4px">
                        Vol.{{ $req->volume }} No.{{ $req->number }} · {{ $req->month }} {{ $req->year }}
                        &nbsp;·&nbsp; No. Reg: <strong>{{ $req->no_reg }}</strong>
                    </div>
                </div>

                {{-- Status detail + aksi --}}
                <div class="col-md-5">
                    @if($req->status === 'approved' && $req->loaValidated)
                    <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:14px">
                        <div style="font-size:.78rem;font-weight:700;color:#166534;margin-bottom:8px">
                            <i class="fas fa-check-circle me-1"></i>LOA Diterbitkan
                        </div>
                        <div style="font-size:.82rem;color:#374151;margin-bottom:4px">
                            Kode LOA: <strong style="color:#166534;font-family:monospace">{{ $req->loaValidated->loa_code }}</strong>
                        </div>
                        @if($req->approved_at)
                        <div style="font-size:.78rem;color:#64748B;margin-bottom:10px">
                            Disetujui: {{ $req->approved_at->format('d M Y') }}
                        </div>
                        @endif
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('loa.view', $req->loaValidated->loa_code) }}"
                               target="_blank"
                               style="background:#16A34A;color:white;padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none">
                                <i class="fas fa-eye me-1"></i>Lihat LOA
                            </a>
                            <a href="{{ route('loa.print', $req->loaValidated->loa_code) }}"
                               target="_blank"
                               style="background:white;border:1.5px solid #16A34A;color:#16A34A;padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none">
                                <i class="fas fa-download me-1"></i>Unduh PDF
                            </a>
                        </div>
                    </div>

                    @elseif($req->status === 'rejected')
                    <div style="background:#FFF5F5;border:1px solid #FECACA;border-radius:10px;padding:14px">
                        <div style="font-size:.78rem;font-weight:700;color:#991B1B;margin-bottom:6px">
                            <i class="fas fa-times-circle me-1"></i>Pengajuan Ditolak
                        </div>
                        @if($req->admin_notes)
                        <div style="font-size:.82rem;color:#374151">
                            <strong>Alasan:</strong> {{ $req->admin_notes }}
                        </div>
                        @else
                        <div style="font-size:.82rem;color:#94A3B8">Tidak ada keterangan dari admin.</div>
                        @endif
                        <a href="{{ route('loa.create') }}"
                           style="display:inline-block;margin-top:10px;background:#DC2626;color:white;padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none">
                            <i class="fas fa-redo me-1"></i>Ajukan Ulang
                        </a>
                    </div>

                    @else
                    <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:14px">
                        <div style="font-size:.78rem;font-weight:700;color:#92400E;margin-bottom:8px">
                            <i class="fas fa-clock me-1"></i>Sedang Diproses
                        </div>
                        {{-- Timeline --}}
                        <div style="display:flex;flex-direction:column;gap:6px">
                            @php
                                $steps = [
                                    ['label'=>'Pengajuan diterima',     'done'=>true],
                                    ['label'=>'Review oleh tim admin',  'done'=>false,'active'=>true],
                                    ['label'=>'LOA diterbitkan',        'done'=>false],
                                ];
                            @endphp
                            @foreach($steps as $step)
                            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem">
                                <div style="width:16px;height:16px;border-radius:50%;background:{{ $step['done'] ? '#22C55E' : ($step['active'] ?? false ? '#EAB308' : '#E2E8F0') }};flex-shrink:0;display:flex;align-items:center;justify-content:center">
                                    @if($step['done'])
                                        <i class="fas fa-check" style="color:white;font-size:.5rem"></i>
                                    @elseif($step['active'] ?? false)
                                        <div style="width:6px;height:6px;background:white;border-radius:50%"></div>
                                    @endif
                                </div>
                                <span style="color:{{ $step['done'] ? '#166534' : ($step['active'] ?? false ? '#92400E' : '#94A3B8') }};font-weight:{{ ($step['active'] ?? false) ? '600' : '400' }}">
                                    {{ $step['label'] }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div style="background:white;border-radius:14px;border:1px solid #E2E8F0;padding:60px;text-align:center">
        <i class="fas fa-file-alt" style="font-size:3rem;color:#CBD5E1;margin-bottom:16px;display:block"></i>
        <h6 style="color:#374151;font-weight:700">Belum Ada Pengajuan LOA</h6>
        <p style="color:#64748B;font-size:.875rem;margin-bottom:20px">
            @if(request()->hasAny(['q','status']))
                Tidak ada hasil yang cocok dengan filter Anda.
            @else
                Anda belum pernah mengajukan LOA. Mulai sekarang!
            @endif
        </p>
        <a href="{{ route('loa.create') }}"
           style="background:linear-gradient(135deg,#4F46E5,#06B6D4);color:white;padding:10px 24px;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none">
            <i class="fas fa-plus me-2"></i>Ajukan LOA Pertama
        </a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($requests->hasPages())
    <div style="margin-top:16px">{{ $requests->links() }}</div>
    @endif

</div>
@endsection
