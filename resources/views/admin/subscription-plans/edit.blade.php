@extends('layouts.admin')

@section('title', 'Edit Paket Langganan')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2 text-warning"></i>Edit Paket: {{ $subscriptionPlan->name }}
        </h1>
    </div>

    <div class="row">
        <div class="col-md-7">
            <form action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}" method="POST" id="planForm">
                @csrf
                @method('PUT')

                {{-- Nama & Deskripsi --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary"><i class="fas fa-tag me-2"></i>Informasi Paket</h6></div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $subscriptionPlan->name) }}" placeholder="mis. Basic, Professional, Enterprise" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="description" rows="2" class="form-control"
                                      placeholder="Cocok untuk publisher dengan kebutuhan...">{{ old('description', $subscriptionPlan->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Harga & Durasi --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary"><i class="fas fa-dollar-sign me-2"></i>Harga & Durasi</h6></div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $subscriptionPlan->price) }}" min="0" step="1000" required>
                                </div>
                                <div class="form-text">Isi 0 untuk gratis</div>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Durasi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror"
                                           value="{{ old('duration_months', $subscriptionPlan->duration_months) }}" min="1" required>
                                    <span class="input-group-text">bulan</span>
                                </div>
                                @error('duration_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Batas Kuota --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary"><i class="fas fa-sliders-h me-2"></i>Batas Kuota</h6></div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Maks. Jurnal</label>
                                <input type="number" name="max_journals" class="form-control @error('max_journals') is-invalid @enderror"
                                       value="{{ old('max_journals', $subscriptionPlan->max_journals) }}" min="1" id="maxJournals"
                                       placeholder="Kosongkan = tidak terbatas">
                                <div class="form-text" id="journalHint"></div>
                                @error('max_journals') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Maks. LOA per Bulan</label>
                                <input type="number" name="max_loa_per_month" class="form-control @error('max_loa_per_month') is-invalid @enderror"
                                       value="{{ old('max_loa_per_month', $subscriptionPlan->max_loa_per_month) }}" min="1" id="maxLoa"
                                       placeholder="Kosongkan = tidak terbatas">
                                <div class="form-text" id="loaHint"></div>
                                @error('max_loa_per_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Urutan Tampil</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $subscriptionPlan->sort_order) }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', $subscriptionPlan->is_active) ? 'checked' : '' }} style="width:2.5em;height:1.25em;">
                                    <label class="form-check-label fw-semibold ms-2" for="is_active">Paket Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fitur --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary"><i class="fas fa-star me-2"></i>Fitur yang Didapat Member</h6></div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @php $currentFeatures = old('features', $subscriptionPlan->features ?? []) @endphp
                            @foreach(\App\Models\SubscriptionPlan::FEATURES as $key => $meta)
                            <div class="col-md-6">
                                <label class="feature-check d-flex align-items-center gap-3 p-3 rounded border cursor-pointer"
                                       style="cursor:pointer;transition:all .15s;"
                                       onmouseenter="this.style.background='#f8f9fa'"
                                       onmouseleave="this.style.background=''">
                                    <input type="checkbox" name="features[]" value="{{ $key }}"
                                           class="form-check-input m-0 flex-shrink-0"
                                           style="width:1.2em;height:1.2em;"
                                           {{ in_array($key, $currentFeatures) ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-semibold small">
                                            <i class="{{ $meta['icon'] }} me-1 text-{{ $meta['color'] }}"></i>
                                            {{ $meta['label'] }}
                                        </div>
                                        @if($key === 'custom_domain')
                                            <div class="text-muted" style="font-size:.75rem;">Publisher bisa pakai subdomain atau domain sendiri</div>
                                        @elseif($key === 'export_csv')
                                            <div class="text-muted" style="font-size:.75rem;">Download data jurnal & LOA ke CSV/Excel</div>
                                        @elseif($key === 'custom_template')
                                            <div class="text-muted" style="font-size:.75rem;">Buat template LOA dengan desain sendiri</div>
                                        @elseif($key === 'priority_support')
                                            <div class="text-muted" style="font-size:.75rem;">Respons admin lebih cepat untuk pertanyaan</div>
                                        @elseif($key === 'analytics')
                                            <div class="text-muted" style="font-size:.75rem;">Statistik pengajuan LOA & aktivitas jurnal</div>
                                        @elseif($key === 'white_label')
                                            <div class="text-muted" style="font-size:.75rem;">Halaman tanpa logo/watermark LOA SIPTENAN</div>
                                        @elseif($key === 'api_access')
                                            <div class="text-muted" style="font-size:.75rem;">Integrasi sistem via REST API</div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>

        {{-- Preview --}}
        <div class="col-md-5">
            <div class="card shadow position-sticky" style="top:80px;">
                <div class="card-header py-3"><h6 class="m-0 fw-bold text-info"><i class="fas fa-eye me-2"></i>Preview Kartu Paket</h6></div>
                <div class="card-body p-4">
                    <div id="previewCard" style="border:2px solid #4F46E5;border-radius:16px;overflow:hidden;">
                        <div style="background:linear-gradient(135deg,#4F46E5,#7C3AED);padding:20px 24px;color:white;">
                            <div style="font-size:1.2rem;font-weight:700;" id="prevName">{{ $subscriptionPlan->name }}</div>
                            <div style="font-size:2rem;font-weight:800;margin:8px 0;" id="prevPrice">{{ $subscriptionPlan->formattedPrice() }}</div>
                            <div style="opacity:.8;font-size:.85rem;" id="prevDuration">per {{ $subscriptionPlan->duration_months }} bulan</div>
                        </div>
                        <div style="padding:20px 24px;">
                            <div class="text-muted small mb-3" id="prevDesc">{{ $subscriptionPlan->description }}</div>
                            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;" id="prevFeatureList">
                                <li style="color:#94A3B8;font-size:.85rem;">— Pilih fitur di kiri —</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const featureMeta = @json(\App\Models\SubscriptionPlan::FEATURES);

function updatePreview() {
    const name  = document.querySelector('[name=name]').value || 'Nama Paket';
    const price = parseInt(document.querySelector('[name=price]').value) || 0;
    const dur   = document.querySelector('[name=duration_months]').value || 12;
    const desc  = document.querySelector('[name=description]').value || '';
    const maxJ  = document.getElementById('maxJournals').value;
    const maxL  = document.getElementById('maxLoa').value;

    document.getElementById('prevName').textContent = name;
    document.getElementById('prevPrice').textContent = price === 0 ? 'Gratis' : 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('prevDuration').textContent = 'per ' + dur + ' bulan';
    document.getElementById('prevDesc').textContent = desc;

    document.getElementById('journalHint').textContent = maxJ ? 'Maksimal ' + maxJ + ' jurnal' : '∞ Tidak terbatas';
    document.getElementById('loaHint').textContent = maxL ? 'Maksimal ' + maxL + ' LOA/bulan' : '∞ Tidak terbatas';

    const checked = Array.from(document.querySelectorAll('[name="features[]"]:checked')).map(el => el.value);
    const list = document.getElementById('prevFeatureList');

    let items = [
        `<li style="font-size:.85rem;display:flex;gap:8px;"><span>📚</span><span><strong>${maxJ || '∞'}</strong> jurnal</span></li>`,
        `<li style="font-size:.85rem;display:flex;gap:8px;"><span>📄</span><span><strong>${maxL || '∞'}</strong> LOA/bulan</span></li>`,
        `<li style="font-size:.85rem;display:flex;gap:8px;"><span>🗓️</span><span>Durasi <strong>${dur} bulan</strong></span></li>`,
    ];
    checked.forEach(key => {
        const m = featureMeta[key];
        if (m) items.push(`<li style="font-size:.85rem;display:flex;gap:8px;color:#059669;"><span>✓</span><span>${m.label}</span></li>`);
    });

    list.innerHTML = items.length ? items.join('') : '<li style="color:#94A3B8;font-size:.85rem;">— Pilih fitur —</li>';
}

document.querySelectorAll('input, textarea').forEach(el => el.addEventListener('input', updatePreview));
document.querySelectorAll('[name="features[]"]').forEach(el => el.addEventListener('change', updatePreview));
updatePreview();
</script>
@endpush
