@extends('publisher.layout')

@section('title', 'Pengaturan Domain')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold"><i class="fas fa-globe me-2 text-primary"></i>Pengaturan Domain Kustom</h4>
    <p class="text-muted mb-0">Gunakan subdomain gratis atau hubungkan domain milik Anda sendiri.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(!$canUseDomain)
{{-- Fitur tidak tersedia di paket ini --}}
<div class="card border-warning mb-4">
    <div class="card-body p-4 text-center">
        <i class="fas fa-lock fa-3x text-warning mb-3"></i>
        <h5 class="fw-bold">Fitur Domain Kustom</h5>
        <p class="text-muted">Fitur ini tersedia mulai paket <strong>Professional</strong> ke atas.<br>
            Upgrade paket Anda untuk menggunakan subdomain atau domain kustom.</p>
        <a href="{{ route('publisher.subscription.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-up me-1"></i>Upgrade Paket
        </a>
    </div>
</div>
@else

{{-- Status Domain Saat Ini --}}
@if($publisher->domain_status !== 'none')
<div class="card mb-4 border-{{ $publisher->domain_status === 'active' ? 'success' : ($publisher->domain_status === 'pending' ? 'warning' : 'danger') }}">
    <div class="card-header bg-{{ $publisher->domain_status === 'active' ? 'success' : ($publisher->domain_status === 'pending' ? 'warning' : 'danger') }} text-white py-2">
        <span class="fw-bold">
            <i class="fas fa-{{ $publisher->domain_status === 'active' ? 'check-circle' : ($publisher->domain_status === 'pending' ? 'clock' : 'times-circle') }} me-2"></i>
            Status Domain:
            @if($publisher->domain_status === 'active') Aktif
            @elseif($publisher->domain_status === 'pending') Menunggu Verifikasi
            @else Ditolak
            @endif
        </span>
    </div>
    <div class="card-body">
        @if($publisher->subdomain)
            <div class="mb-2">
                <span class="text-muted small">URL Portal:</span>
                <strong class="d-block">{{ config('app.base_domain') }}/publishers/{{ $publisher->subdomain }}</strong>
            </div>
        @elseif($publisher->custom_domain)
            <div class="mb-2">
                <span class="text-muted small">Domain Kustom:</span>
                <strong class="d-block">{{ $publisher->custom_domain }}</strong>
            </div>
        @endif

        @if($publisher->domain_status === 'active')
            @php $url = $publisher->getPublicDomainUrl() @endphp
            @if($url)
            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-success mt-1">
                <i class="fas fa-external-link-alt me-1"></i>Buka Portal Domain
            </a>
            @endif
        @elseif($publisher->domain_status === 'pending')
            <p class="text-muted mb-2 small">Permintaan Anda sedang diproses oleh admin. Harap tunggu konfirmasi.</p>
        @elseif($publisher->domain_status === 'rejected')
            @if($publisher->domain_notes)
            <div class="alert alert-danger mb-2 py-2 small">
                <strong>Alasan penolakan:</strong> {{ $publisher->domain_notes }}
            </div>
            @endif
            <p class="text-muted small mb-2">Anda dapat mengajukan permintaan baru di bawah ini.</p>
        @endif

        @if(in_array($publisher->domain_status, ['pending', 'active']))
        <form action="{{ route('publisher.domain.cancel') }}" method="POST" class="mt-2"
              onsubmit="return confirm('Yakin membatalkan domain ini?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-times me-1"></i>Batalkan Domain
            </button>
        </form>
        @endif
    </div>
</div>
@endif

@if(in_array($publisher->domain_status, ['none', 'rejected']))
{{-- Form Pengajuan Domain --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Ajukan Domain</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('publisher.domain.update') }}" method="POST">
            @csrf

            {{-- Pilih Jenis Domain --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Jenis Domain</label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="d-flex align-items-start gap-3 p-3 rounded border cursor-pointer domain-type-card"
                               style="cursor:pointer;" for="typeSubdomain">
                            <input type="radio" name="type" id="typeSubdomain" value="subdomain" class="form-check-input mt-1 flex-shrink-0"
                                   {{ old('type', 'subdomain') === 'subdomain' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-semibold"><i class="fas fa-link me-1 text-primary"></i>URL Portal Gratis</div>
                                <div class="text-muted" style="font-size:.8rem;">
                                    <strong>{{ config('app.base_domain') }}</strong>/publishers/nama-anda<br>
                                    Lebih mudah, langsung aktif setelah disetujui.
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="d-flex align-items-start gap-3 p-3 rounded border cursor-pointer domain-type-card"
                               style="cursor:pointer;" for="typeCustom">
                            <input type="radio" name="type" id="typeCustom" value="custom_domain" class="form-check-input mt-1 flex-shrink-0"
                                   {{ old('type') === 'custom_domain' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-semibold"><i class="fas fa-globe me-1 text-success"></i>Domain Kustom</div>
                                <div class="text-muted" style="font-size:.8rem;">
                                    mis. <strong>loa.namajournal.ac.id</strong><br>
                                    Perlu pointing DNS ke server kami.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Input Subdomain --}}
            <div id="subdomainInput" class="mb-4">
                <label class="form-label fw-semibold">Nama URL Portal</label>
                <div class="input-group">
                    <span class="input-group-text text-muted" style="font-size:.85rem;">{{ config('app.base_domain') }}/publishers/</span>
                    <input type="text" name="subdomain" class="form-control @error('subdomain') is-invalid @enderror"
                           value="{{ old('subdomain', $publisher->subdomain) }}"
                           placeholder="nama-publisher" pattern="[a-zA-Z0-9\-]+" minlength="3" maxlength="50">
                </div>
                @error('subdomain') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text">Hanya huruf kecil, angka, dan tanda hubung (-). Minimal 3 karakter.</div>
            </div>

            {{-- Input Custom Domain --}}
            <div id="customDomainInput" class="mb-4" style="display:none;">
                <label class="form-label fw-semibold">Nama Domain</label>
                <input type="text" name="custom_domain" class="form-control @error('custom_domain') is-invalid @enderror"
                       value="{{ old('custom_domain', $publisher->custom_domain) }}"
                       placeholder="loa.namajournal.ac.id">
                @error('custom_domain') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text">Contoh: loa.namajournal.ac.id atau journal.namaanda.org</div>

                {{-- Instruksi DNS --}}
                <div class="alert alert-info mt-3 small">
                    <strong><i class="fas fa-info-circle me-1"></i>Cara Menghubungkan Domain:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Ajukan domain di form ini</li>
                        <li>Tambahkan record DNS berikut di registrar domain Anda:<br>
                            <code>A record → {{ gethostbyname(config('app.base_domain')) }}</code>
                        </li>
                        <li>Admin akan memverifikasi dan mengaktifkan domain Anda</li>
                    </ol>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i>Ajukan Domain
            </button>
        </form>
    </div>
</div>
@endif

@endif {{-- end canUseDomain --}}

{{-- Info Umum --}}
<div class="card border-0 bg-light">
    <div class="card-body small text-muted">
        <i class="fas fa-question-circle me-1"></i><strong>Tentang Domain Kustom:</strong>
        Setelah domain diaktifkan, pengunjung yang mengakses domain tersebut akan melihat portal LOA khusus untuk publisher Anda,
        termasuk daftar jurnal dan fitur verifikasi LOA. Fitur ini memerlukan paket Professional atau Enterprise.
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDomainType() {
    const isCustom = document.getElementById('typeCustom').checked;
    document.getElementById('subdomainInput').style.display  = isCustom ? 'none' : '';
    document.getElementById('customDomainInput').style.display = isCustom ? '' : 'none';
}
document.querySelectorAll('[name="type"]').forEach(r => r.addEventListener('change', toggleDomainType));
toggleDomainType();

// Highlight active card
document.querySelectorAll('.domain-type-card input').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('.domain-type-card').forEach(c => c.style.borderColor = '');
        if (r.checked) r.closest('.domain-type-card').style.borderColor = '#4F46E5';
    });
    if (r.checked) r.closest('.domain-type-card').style.borderColor = '#4F46E5';
});
</script>
@endpush
