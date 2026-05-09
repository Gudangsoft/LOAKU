@extends('layouts.app')

@section('title', $publisher->name . ' - LOA SIPTENAN')

@push('styles')
<style>
    .pub-hero {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 60%, #1E40AF 100%);
        color: white;
        padding: 48px 0 44px;
        margin-bottom: -44px;
    }

    .pub-logo-wrap {
        width: 88px; height: 88px;
        background: white;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(0,0,0,.2);
        flex-shrink: 0;
        overflow: hidden;
        padding: 8px;
    }

    .pub-logo-placeholder {
        width: 72px; height: 72px;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 800;
    }

    .pub-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(16,185,129,.2);
        border: 1px solid rgba(16,185,129,.4);
        color: #6EE7B7;
        font-size: .75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 10px;
    }

    .pub-name {
        font-size: clamp(1.4rem, 3vw, 1.9rem);
        font-weight: 800;
        margin-bottom: 8px;
    }

    .pub-contact-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 20px;
        padding: 5px 14px;
        font-size: .8rem;
        color: #BAE6FD;
        text-decoration: none;
        transition: background .2s;
        margin-right: 6px;
        margin-top: 6px;
        display: inline-flex;
    }
    .pub-contact-pill:hover { background: rgba(255,255,255,.2); color: white; }

    /* Content */
    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        padding: 24px;
        margin-bottom: 20px;
    }

    .info-card-title {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #4F46E5;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #EEF2FF;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #F1F5F9;
        font-size: .875rem;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }

    .info-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        flex-shrink: 0;
    }

    .info-label { font-size: .75rem; color: #94A3B8; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
    .info-value { color: #1E293B; font-weight: 500; margin-top: 1px; }

    /* Journal cards */
    .journal-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #E2E8F0;
        padding: 20px;
        transition: all .25s;
        height: 100%;
    }
    .journal-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,.08);
        border-color: #C7D2FE;
        transform: translateY(-2px);
    }

    .journal-logo-wrap {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #EEF2FF, #C7D2FE);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #4F46E5;
        flex-shrink: 0;
        overflow: hidden;
    }

    .journal-name {
        font-size: .95rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .issn-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .72rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        margin-right: 4px;
        margin-bottom: 4px;
    }
    .issn-badge.e { background: #EEF2FF; color: #4F46E5; }
    .issn-badge.p { background: #ECFDF5; color: #059669; }
    .sinta-badge { background: #FEF3C7; color: #92400E; }
    .doi-badge   { background: #F0FDF4; color: #166534; }
    .garuda-badge{ background: #EFF6FF; color: #1D4ED8; }

    .journal-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: .8rem;
        color: #64748B;
        margin-top: 6px;
    }

    .btn-request {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        margin-top: 12px;
    }
    .btn-request:hover { opacity: .9; transform: translateY(-1px); color: white; }

    .sticky-sidebar { position: sticky; top: 80px; }

    .stat-pill {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .stat-pill-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
    }
    .stat-pill-num { font-size: 1.3rem; font-weight: 800; color: #1E293B; line-height: 1; }
    .stat-pill-label { font-size: .75rem; color: #64748B; }
</style>
@endpush

@section('content')

<!-- Publisher Hero -->
<div class="pub-hero">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:16px">
            <ol class="breadcrumb mb-0" style="font-size:.8rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#BAE6FD;text-decoration:none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('publishers.index') }}" style="color:#BAE6FD;text-decoration:none">Publisher</a></li>
                <li class="breadcrumb-item active" style="color:#E0E7FF">{{ Str::limit($publisher->name, 30) }}</li>
            </ol>
        </nav>

        <div class="d-flex align-items-start gap-4">
            <div class="pub-logo-wrap">
                @if($publisher->logo)
                    <img src="{{ asset('storage/' . $publisher->logo) }}"
                         alt="{{ $publisher->name }}"
                         style="max-width:100%;max-height:100%;object-fit:contain;">
                @else
                    <div class="pub-logo-placeholder">
                        {{ strtoupper(substr($publisher->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <div class="pub-badge">
                    <i class="fas fa-check-circle" style="font-size:.7rem"></i> Publisher Aktif & Terverifikasi
                </div>
                <h1 class="pub-name">{{ $publisher->name }}</h1>
                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:8px">
                    @if($publisher->phone)
                    <span class="pub-contact-pill">
                        <i class="fas fa-phone" style="font-size:.75rem"></i> {{ $publisher->phone }}
                    </span>
                    @endif
                    @if($publisher->email)
                    <a href="mailto:{{ $publisher->email }}" class="pub-contact-pill">
                        <i class="fas fa-envelope" style="font-size:.75rem"></i> {{ $publisher->email }}
                    </a>
                    @endif
                    @if($publisher->website)
                    <a href="{{ $publisher->website }}" target="_blank" class="pub-contact-pill">
                        <i class="fas fa-globe" style="font-size:.75rem"></i> Website
                    </a>
                    @endif
                    @if($publisher->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $publisher->whatsapp) }}" target="_blank" class="pub-contact-pill">
                        <i class="fab fa-whatsapp" style="font-size:.75rem"></i> WhatsApp
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5" style="padding-top:60px!important">
    <div class="row g-4">

        <!-- Main Content -->
        <div class="col-lg-8">

            <!-- Info Card -->
            <div class="info-card">
                <div class="info-card-title">
                    <i class="fas fa-info-circle"></i> Informasi Publisher
                </div>

                @if($publisher->address)
                <div class="info-row">
                    <div class="info-icon" style="background:#EEF2FF;color:#4F46E5">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $publisher->address }}</div>
                    </div>
                </div>
                @endif

                @if($publisher->email)
                <div class="info-row">
                    <div class="info-icon" style="background:#FFFBEB;color:#D97706">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:{{ $publisher->email }}" style="color:#4F46E5;text-decoration:none">{{ $publisher->email }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($publisher->phone)
                <div class="info-row">
                    <div class="info-icon" style="background:#ECFDF5;color:#059669">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div class="info-label">Telepon</div>
                        <div class="info-value">{{ $publisher->phone }}</div>
                    </div>
                </div>
                @endif

                @if($publisher->whatsapp)
                <div class="info-row">
                    <div class="info-icon" style="background:#DCFCE7;color:#16A34A">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <div class="info-label">WhatsApp</div>
                        <div class="info-value">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $publisher->whatsapp) }}" target="_blank"
                               style="color:#16A34A;text-decoration:none;font-weight:600">
                                {{ $publisher->whatsapp }} <i class="fas fa-external-link-alt" style="font-size:.7rem"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($publisher->website)
                <div class="info-row">
                    <div class="info-icon" style="background:#DBEAFE;color:#2563EB">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <div class="info-label">Website</div>
                        <div class="info-value">
                            <a href="{{ $publisher->website }}" target="_blank"
                               style="color:#2563EB;text-decoration:none">
                                {{ $publisher->website }} <i class="fas fa-external-link-alt" style="font-size:.7rem"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($publisher->validated_at)
                <div class="info-row">
                    <div class="info-icon" style="background:#ECFDF5;color:#059669">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="info-label">Tanggal Verifikasi</div>
                        <div class="info-value">{{ $publisher->validated_at->format('d F Y') }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Journals -->
            <div class="info-card">
                <div class="info-card-title">
                    <i class="fas fa-book-open"></i> Jurnal Terdaftar ({{ $publisher->journals->count() }})
                </div>

                @if($publisher->journals->count() > 0)
                <div class="row g-3">
                    @foreach($publisher->journals as $journal)
                    <div class="col-md-6">
                        <div class="journal-card">
                            <div class="d-flex align-items-start gap-3 mb-2">
                                <div class="journal-logo-wrap">
                                    @if($journal->logo)
                                        <img src="{{ asset('storage/' . $journal->logo) }}"
                                             alt="{{ $journal->name }}"
                                             style="width:100%;height:100%;object-fit:contain;">
                                    @else
                                        <i class="fas fa-book"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="journal-name">{{ $journal->name }}</div>
                                    <div>
                                        @if($journal->e_issn)
                                        <span class="issn-badge e">e-ISSN: {{ $journal->e_issn }}</span>
                                        @endif
                                        @if($journal->p_issn)
                                        <span class="issn-badge p">p-ISSN: {{ $journal->p_issn }}</span>
                                        @endif
                                        @if(!empty($journal->accreditation_level) && $journal->accreditation_level !== 'none')
                                        <span class="issn-badge sinta-badge"><i class="fas fa-star" style="font-size:.65rem"></i> SINTA {{ $journal->accreditation_level }}</span>
                                        @endif
                                        @if(!empty($journal->sinta_id))
                                        <a href="https://sinta.kemdikbud.go.id/journals/detail?id={{ $journal->sinta_id }}" target="_blank" class="issn-badge sinta-badge text-decoration-none"><i class="fas fa-external-link-alt" style="font-size:.6rem"></i> SINTA</a>
                                        @endif
                                        @if(!empty($journal->doi_prefix))
                                        <span class="issn-badge doi-badge"><i class="fas fa-fingerprint" style="font-size:.65rem"></i> DOI: {{ $journal->doi_prefix }}</span>
                                        @endif
                                        @if(!empty($journal->garuda_id))
                                        <a href="https://garuda.kemdikbud.go.id/journal/view/{{ $journal->garuda_id }}" target="_blank" class="issn-badge garuda-badge text-decoration-none"><i class="fas fa-external-link-alt" style="font-size:.6rem"></i> Garuda</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($journal->chief_editor)
                            <div class="journal-meta-row">
                                <i class="fas fa-user-tie" style="color:#4F46E5;font-size:.75rem"></i>
                                <span>Editor: {{ $journal->chief_editor }}</span>
                            </div>
                            @endif

                            @if($journal->email)
                            <div class="journal-meta-row">
                                <i class="fas fa-envelope" style="color:#D97706;font-size:.75rem"></i>
                                <a href="mailto:{{ $journal->email }}" style="color:#64748B;text-decoration:none">{{ $journal->email }}</a>
                            </div>
                            @endif

                            @if($journal->website)
                            <div class="journal-meta-row">
                                <i class="fas fa-globe" style="color:#2563EB;font-size:.75rem"></i>
                                <a href="{{ $journal->website }}" target="_blank" style="color:#2563EB;text-decoration:none">
                                    Kunjungi Website <i class="fas fa-external-link-alt" style="font-size:.65rem"></i>
                                </a>
                            </div>
                            @endif

                            @if($journal->description)
                            <p style="font-size:.78rem;color:#64748B;margin-top:8px;margin-bottom:0;line-height:1.55">
                                {{ Str::limit($journal->description, 100) }}
                            </p>
                            @endif

                            <a href="{{ route('loa.create') }}?journal_id={{ $journal->id }}" class="btn-request">
                                <i class="fas fa-paper-plane"></i> Request LOA ke Jurnal Ini
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center;padding:32px;color:#64748B;font-size:.875rem">
                    <i class="fas fa-book-open" style="font-size:2rem;color:#CBD5E1;display:block;margin-bottom:10px"></i>
                    Belum ada jurnal terdaftar
                </div>
                @endif
            </div>
            <!-- Issued LOAs -->
            @php
                $journalIds = $publisher->journals->pluck('id');
                $issuedLoas = \App\Models\LoaValidated::whereHas('loaRequest', fn($q) => $q->whereIn('journal_id', $journalIds))
                    ->with(['loaRequest'])
                    ->orderByDesc('created_at')
                    ->take(10)
                    ->get();
            @endphp
            @if($issuedLoas->count() > 0)
            <div class="info-card">
                <div class="info-card-title">
                    <i class="fas fa-certificate"></i> LOA Terbaru yang Diterbitkan ({{ $issuedLoas->count() }} terakhir)
                </div>
                <div style="display:flex;flex-direction:column;gap:10px">
                    @foreach($issuedLoas as $loa)
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:12px 14px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                        <div style="min-width:0;flex:1">
                            <div style="font-size:.8rem;font-weight:700;color:#166534;font-family:monospace;margin-bottom:3px">{{ $loa->loa_code }}</div>
                            <div style="font-size:.82rem;color:#374151;line-height:1.4;margin-bottom:3px">{{ \Illuminate\Support\Str::limit($loa->loaRequest?->article_title ?? '-', 70) }}</div>
                            <div style="font-size:.75rem;color:#64748B">{{ $loa->loaRequest?->author ?? '-' }} · {{ $loa->created_at->format('d M Y') }}</div>
                        </div>
                        <a href="{{ route('loa.view', $loa->loa_code) }}" target="_blank"
                           style="background:#16A34A;color:white;padding:5px 12px;border-radius:7px;font-size:.75rem;font-weight:600;text-decoration:none;white-space:nowrap;flex-shrink:0">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <!-- Stats -->
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-chart-bar"></i> Statistik
                    </div>
                    <div class="stat-pill">
                        <div class="stat-pill-icon" style="background:#EEF2FF;color:#4F46E5">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <div class="stat-pill-num">{{ $publisher->journals->count() }}</div>
                            <div class="stat-pill-label">Jurnal Terdaftar</div>
                        </div>
                    </div>
                    <div class="stat-pill">
                        <div class="stat-pill-icon" style="background:#ECFDF5;color:#059669">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            @php $totalRequests = $publisher->journals->sum('loa_requests_count'); @endphp
                            <div class="stat-pill-num">{{ $totalRequests }}</div>
                            <div class="stat-pill-label">Total Permintaan LOA</div>
                        </div>
                    </div>
                    <div class="stat-pill">
                        <div class="stat-pill-icon" style="background:#ECFDF5;color:#059669">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            @php
                                $approvedRequests = $publisher->journals->sum(function($j) {
                                    return $j->loaRequests->where('status','approved')->count();
                                });
                            @endphp
                            <div class="stat-pill-num">{{ $approvedRequests }}</div>
                            <div class="stat-pill-label">LOA Disetujui</div>
                        </div>
                    </div>
                </div>

                <!-- Widget Embed -->
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-code"></i> Pasang Badge LOA di Website
                    </div>

                    @if(isset($issuedLoas) && $issuedLoas->count() > 0)
                    <p style="font-size:.78rem;color:#64748B;margin-bottom:12px;line-height:1.5">
                        Klik <strong>Salin</strong> di LOA yang ingin ditampilkan, lalu tempelkan kode tersebut di halaman artikel Anda.
                    </p>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @foreach($issuedLoas as $loa)
                        @php
                            $scriptTag = '<script src="' . rtrim(config('app.url'),'/') . '/widget/' . $loa->loa_code . '.js"></script>';
                        @endphp
                        <div style="border:1px solid #E2E8F0;border-radius:10px;overflow:hidden">
                            {{-- LOA info --}}
                            <div style="padding:8px 12px;background:#F8FAFC;border-bottom:1px solid #E2E8F0">
                                <div style="font-size:.8rem;font-weight:700;color:#166534;font-family:monospace">{{ $loa->loa_code }}</div>
                                <div style="font-size:.73rem;color:#64748B;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ \Illuminate\Support\Str::limit($loa->loaRequest?->article_title ?? '-', 45) }}
                                </div>
                            </div>
                            {{-- Script tag + copy button --}}
                            <div style="padding:8px 10px;display:flex;align-items:center;gap:8px;background:white">
                                <code style="font-size:.65rem;color:#475569;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $scriptTag }}</code>
                                <button onclick="copyWidget('widget-{{ $loa->loa_code }}', this)"
                                        data-code="{{ htmlspecialchars($scriptTag, ENT_QUOTES) }}"
                                        id="widget-{{ $loa->loa_code }}"
                                        style="background:#4F46E5;color:white;border:none;border-radius:6px;padding:5px 10px;font-size:.72rem;font-weight:600;cursor:pointer;white-space:nowrap;flex-shrink:0">
                                    <i class="fas fa-copy me-1"></i>Salin
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @else
                    <div style="text-align:center;padding:20px 0;color:#94A3B8">
                        <i class="fas fa-certificate" style="font-size:1.8rem;margin-bottom:8px;display:block;color:#CBD5E1"></i>
                        <p style="font-size:.8rem;margin:0">Belum ada LOA diterbitkan.<br>Badge akan muncul setelah LOA pertama disetujui.</p>
                    </div>
                    @endif
                </div>

                <!-- Quick action -->
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-bolt"></i> Aksi Cepat
                    </div>
                    <a href="{{ route('loa.create') }}" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;border:1px solid #E2E8F0;margin-bottom:8px"
                       onmouseover="this.style.background='#F8FAFC';this.style.borderColor='#C7D2FE'" onmouseout="this.style.background='transparent';this.style.borderColor='#E2E8F0'">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#4F46E5,#06B6D4);border-radius:9px;display:flex;align-items:center;justify-content:center;color:white;font-size:.85rem;flex-shrink:0">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600">Request LOA</div>
                            <div style="font-size:.75rem;color:#64748B">Ajukan permintaan ke publisher ini</div>
                        </div>
                    </a>
                    <a href="{{ route('loa.search') }}" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;border:1px solid #E2E8F0;margin-bottom:8px"
                       onmouseover="this.style.background='#F8FAFC';this.style.borderColor='#A7F3D0'" onmouseout="this.style.background='transparent';this.style.borderColor='#E2E8F0'">
                        <div style="width:36px;height:36px;background:#ECFDF5;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#059669;font-size:.85rem;flex-shrink:0">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600">Cari LOA Saya</div>
                            <div style="font-size:.75rem;color:#64748B">Temukan LOA yang sudah disetujui</div>
                        </div>
                    </a>
                    <a href="{{ route('publishers.index') }}" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#374151;transition:background .2s;border:1px solid #E2E8F0"
                       onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                        <div style="width:36px;height:36px;background:#F1F5F9;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#64748B;font-size:.85rem;flex-shrink:0">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600">Semua Publisher</div>
                            <div style="font-size:.75rem;color:#64748B">Kembali ke daftar publisher</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@push('scripts')
<script>
function copyWidget(btnId, btn) {
    var code = btn.getAttribute('data-code');
    navigator.clipboard.writeText(code).then(function () {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Tersalin!';
        btn.style.background = '#16A34A';
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.style.background = '#4F46E5';
        }, 2000);
    }).catch(function () {
        // fallback for older browsers
        var el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Tersalin!';
        btn.style.background = '#16A34A';
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.style.background = '#4F46E5';
        }, 2000);
    });
}
</script>
@endpush

@endsection
