@extends('layouts.app')

@section('title', 'Daftar Publisher - LOA SIPTENAN')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 60%, #1E40AF 100%);
        color: white;
        padding: 52px 0 44px;
        margin-bottom: -44px;
    }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 6px; }
    .page-header p  { color: #BAE6FD; font-size: 0.9rem; margin: 0; }

    .search-wrap {
        position: relative;
        max-width: 420px;
    }
    .search-wrap input {
        border: 2px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.12);
        color: white;
        border-radius: 12px;
        padding: 10px 16px 10px 42px;
        font-size: 0.9rem;
        width: 100%;
        transition: border-color .2s, background .2s;
    }
    .search-wrap input::placeholder { color: rgba(255,255,255,.55); }
    .search-wrap input:focus {
        outline: none;
        border-color: rgba(255,255,255,.6);
        background: rgba(255,255,255,.2);
    }
    .search-wrap .si {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,.6);
        font-size: .85rem;
        pointer-events: none;
    }
    .search-wrap button {
        position: absolute;
        right: 6px; top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,.2);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s;
    }
    .search-wrap button:hover { background: rgba(255,255,255,.35); }

    .publisher-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
        height: 100%;
        transition: all .3s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }
    .publisher-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,.1);
        border-color: #C7D2FE;
        color: inherit;
    }

    .publisher-logo-wrap {
        background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 100%);
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #E2E8F0;
        padding: 16px;
        position: relative;
    }

    .publisher-logo-placeholder {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        font-weight: 800;
    }

    .status-dot {
        position: absolute;
        top: 12px; right: 12px;
        width: 8px; height: 8px;
        background: #10B981;
        border-radius: 50%;
        box-shadow: 0 0 0 2px white, 0 0 0 4px #A7F3D0;
    }

    .publisher-body {
        padding: 18px 20px;
        flex: 1;
    }

    .publisher-body h5 {
        font-size: .95rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .publisher-meta {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: .8rem;
        color: #64748B;
        margin-bottom: 5px;
    }
    .publisher-meta i { margin-top: 2px; width: 14px; flex-shrink: 0; }

    .publisher-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
    }

    .journal-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #EEF2FF;
        color: #4F46E5;
        font-size: .75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
    }

    .detail-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .78rem;
        font-weight: 600;
        color: #4F46E5;
        text-decoration: none;
    }
    .detail-link:hover { color: #3730A3; }

    .publisher-links { display: flex; gap: 5px; }
    .publisher-link-btn {
        width: 28px; height: 28px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        text-decoration: none;
        transition: all .2s;
    }
    .publisher-link-btn.wa    { background: #DCFCE7; color: #16A34A; }
    .publisher-link-btn.wa:hover    { background: #16A34A; color: white; }
    .publisher-link-btn.web   { background: #DBEAFE; color: #2563EB; }
    .publisher-link-btn.web:hover   { background: #2563EB; color: white; }
    .publisher-link-btn.email { background: #FEF3C7; color: #D97706; }
    .publisher-link-btn.email:hover { background: #D97706; color: white; }

    .empty-state {
        text-align: center;
        padding: 80px 0;
    }
    .empty-icon {
        width: 80px; height: 80px;
        background: #EEF2FF;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 2rem; color: #4F46E5;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:12px">
            <ol class="breadcrumb mb-0" style="font-size:.8rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#BAE6FD;text-decoration:none">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:#E0E7FF">Publisher</li>
            </ol>
        </nav>
        <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-building me-3" style="font-size:1.4rem"></i>Publisher Terdaftar</h1>
                <p>{{ $totalPublishers }} publisher aktif bermitra dengan LOA SIPTENAN</p>
            </div>
            <form action="{{ route('publishers.index') }}" method="GET" style="margin-bottom:4px">
                <div class="search-wrap">
                    <i class="fas fa-search si"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari publisher...">
                    @if($search)
                    <a href="{{ route('publishers.index') }}" style="position:absolute;right:6px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.7);font-size:.85rem;line-height:1;cursor:pointer;text-decoration:none">
                        <i class="fas fa-times"></i>
                    </a>
                    @else
                    <button type="submit">Cari</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container py-5" style="padding-top:60px!important">

    @if($search)
    <div style="background:#EEF2FF;border:1px solid #C7D2FE;border-radius:10px;padding:10px 16px;margin-bottom:24px;font-size:.875rem;color:#3730A3;display:inline-flex;align-items:center;gap:8px">
        <i class="fas fa-search"></i>
        Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
        <a href="{{ route('publishers.index') }}" style="color:#EF4444;text-decoration:none;margin-left:4px"><i class="fas fa-times"></i></a>
    </div>
    @endif

    @if($publishers->count() > 0)
    <div class="row g-4">
        @foreach($publishers as $publisher)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('publishers.detail', $publisher->id) }}" class="publisher-card">
                <div class="publisher-logo-wrap">
                    <div class="status-dot" title="Publisher Aktif"></div>
                    @if($publisher->logo)
                        <img src="{{ asset('storage/' . $publisher->logo) }}"
                             alt="{{ $publisher->name }}"
                             style="max-height:72px;max-width:160px;object-fit:contain;">
                    @else
                        <div class="publisher-logo-placeholder">
                            {{ strtoupper(substr($publisher->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="publisher-body">
                    <h5>{{ $publisher->name }}</h5>
                    @if($publisher->address)
                    <div class="publisher-meta">
                        <i class="fas fa-map-marker-alt" style="color:#4F46E5"></i>
                        <span>{{ Str::limit($publisher->address, 60) }}</span>
                    </div>
                    @endif
                    @if($publisher->email)
                    <div class="publisher-meta">
                        <i class="fas fa-envelope" style="color:#F59E0B"></i>
                        <span>{{ $publisher->email }}</span>
                    </div>
                    @endif
                    @if($publisher->phone)
                    <div class="publisher-meta">
                        <i class="fas fa-phone" style="color:#10B981"></i>
                        <span>{{ $publisher->phone }}</span>
                    </div>
                    @endif
                </div>
                <div class="publisher-footer">
                    <div class="journal-badge">
                        <i class="fas fa-book"></i>
                        {{ $publisher->journals_count }} Jurnal
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="publisher-links" onclick="event.preventDefault()">
                            @if($publisher->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $publisher->whatsapp) }}"
                               target="_blank" class="publisher-link-btn wa" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            @endif
                            @if($publisher->website)
                            <a href="{{ $publisher->website }}" target="_blank" class="publisher-link-btn web" title="Website">
                                <i class="fas fa-globe"></i>
                            </a>
                            @endif
                        </div>
                        <span class="detail-link">
                            Detail <i class="fas fa-arrow-right" style="font-size:.7rem"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $publishers->appends(['q' => $search])->links('pagination::bootstrap-5') }}
    </div>

    @else
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-building"></i></div>
        <h5 style="color:#1E293B;font-weight:700">
            @if($search) Tidak Ada Hasil @else Belum Ada Publisher @endif
        </h5>
        <p style="color:#64748B;margin-bottom:20px">
            @if($search)
                Tidak ada publisher yang cocok dengan "<strong>{{ $search }}</strong>".
            @else
                Publisher belum terdaftar saat ini.
            @endif
        </p>
        @if($search)
        <a href="{{ route('publishers.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-times me-1"></i> Hapus Filter
        </a>
        @else
        <a href="{{ route('publisher.register.form') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Daftar sebagai Publisher
        </a>
        @endif
    </div>
    @endif
</div>
@endsection
