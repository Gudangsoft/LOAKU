@extends('layouts.admin')

@section('title', 'Pengaturan Rekening Pembayaran')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-university me-2 text-primary"></i>Pengaturan Pembayaran
        </h1>
    </div>

    <form action="{{ route('admin.payment-settings.update') }}" method="POST" enctype="multipart/form-data" id="payForm">
        @csrf
        <div class="row">
            <div class="col-lg-7">

                {{-- ── REKENING BANK ── --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-credit-card me-2"></i>Rekening Bank</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addBank">
                            <i class="fas fa-plus me-1"></i>Tambah Rekening
                        </button>
                    </div>
                    <div class="card-body p-0" id="bankList">
                        @foreach($accounts as $i => $acc)
                        <div class="bank-row border-bottom p-4" data-index="{{ $i }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="fw-semibold text-muted small">Rekening #<span class="row-num">{{ $i + 1 }}</span></span>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-bank"
                                        {{ count($accounts) <= 1 ? 'style=display:none' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Nama Bank</label>
                                    <input type="text" name="banks[{{ $i }}][bank_name]" class="form-control form-control-sm"
                                           value="{{ $acc['bank_name'] ?? '' }}" placeholder="BCA, Mandiri, BRI...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Nomor Rekening</label>
                                    <input type="text" name="banks[{{ $i }}][account_number]" class="form-control form-control-sm"
                                           value="{{ $acc['account_number'] ?? '' }}" placeholder="1234567890">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Atas Nama</label>
                                    <input type="text" name="banks[{{ $i }}][account_name]" class="form-control form-control-sm"
                                           value="{{ $acc['account_name'] ?? '' }}" placeholder="Nama pemilik">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Logo Bank <span class="text-muted">(opsional)</span></label>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        @if(!empty($acc['logo']))
                                        <div class="logo-preview-wrap" id="logoPreview{{ $i }}">
                                            <img src="{{ Storage::url($acc['logo']) }}" style="max-height:40px;max-width:80px;" class="img-thumbnail">
                                            <input type="hidden" name="delete_logo[{{ $i }}]" value="0" id="deleteLogo{{ $i }}">
                                            <button type="button" class="btn btn-xs btn-outline-danger ms-1 py-0 px-1"
                                                    onclick="removeLogo({{ $i }})"><i class="fas fa-times"></i></button>
                                        </div>
                                        @else
                                        <div id="logoPreview{{ $i }}"></div>
                                        @endif
                                        <input type="file" name="logo[{{ $i }}]" class="form-control form-control-sm"
                                               accept="image/*" style="max-width:260px;"
                                               onchange="previewLogo(this, {{ $i }})">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── WHATSAPP ── --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-success"><i class="fab fa-whatsapp me-2"></i>WhatsApp Konfirmasi</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor WhatsApp Admin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                <input type="text" name="payment_whatsapp" class="form-control @error('payment_whatsapp') is-invalid @enderror"
                                       value="{{ old('payment_whatsapp', $whatsapp) }}"
                                       placeholder="628123456789 (tanpa + atau spasi)">
                            </div>
                            @error('payment_whatsapp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <div class="form-text">Format: kode negara + nomor, contoh <code>628123456789</code></div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Template Pesan WA</label>
                            <textarea name="payment_whatsapp_message" rows="3" class="form-control"
                                      placeholder="Pesan otomatis saat publisher klik tombol WA...">{{ old('payment_whatsapp_message', $waMessage) }}</textarea>
                            <div class="form-text">
                                Variabel tersedia: <code>{invoice}</code> <code>{plan}</code> <code>{amount}</code> <code>{name}</code>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── INSTRUKSI ── --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Instruksi Pembayaran</h6>
                    </div>
                    <div class="card-body p-4">
                        <textarea name="payment_instructions" rows="4" class="form-control"
                                  placeholder="Panduan pembayaran untuk publisher...">{{ old('payment_instructions', $instructions) }}</textarea>
                        <div class="form-text">Teks ini ditampilkan di email invoice dan halaman pembayaran publisher.</div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i>Simpan Pengaturan
                    </button>
                </div>
            </div>

            {{-- ── PREVIEW ── --}}
            <div class="col-lg-5">
                <div class="card shadow position-sticky" style="top:80px;">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-info"><i class="fas fa-eye me-2"></i>Preview Info Pembayaran</h6>
                    </div>
                    <div class="card-body p-4">
                        <div id="previewWrap" style="background:#f8faff;border-radius:12px;padding:20px;border:1px solid #e2e8f0;">
                            <p class="small text-muted fw-semibold mb-3">Informasi Pembayaran:</p>
                            <div id="previewBanks"></div>
                            @if($whatsapp)
                            <div class="mt-3">
                                <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
                                   class="btn btn-success btn-sm w-100">
                                    <i class="fab fa-whatsapp me-1"></i>Konfirmasi via WhatsApp
                                </a>
                            </div>
                            @endif
                            <div id="previewInstructions" class="mt-2 small text-muted"></div>
                        </div>

                        <div class="mt-3 p-3 border rounded small bg-light">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            <strong>Tombol WA Publisher</strong> — publisher akan melihat tombol WhatsApp di halaman pembayaran mereka untuk konfirmasi langsung ke admin.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Template row untuk tambah rekening --}}
<template id="bankRowTemplate">
    <div class="bank-row border-bottom p-4" data-index="__IDX__">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <span class="fw-semibold text-muted small">Rekening #<span class="row-num">__NUM__</span></span>
            <button type="button" class="btn btn-sm btn-outline-danger remove-bank">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Nama Bank</label>
                <input type="text" name="banks[__IDX__][bank_name]" class="form-control form-control-sm" placeholder="BCA, Mandiri, BRI...">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Nomor Rekening</label>
                <input type="text" name="banks[__IDX__][account_number]" class="form-control form-control-sm" placeholder="1234567890">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Atas Nama</label>
                <input type="text" name="banks[__IDX__][account_name]" class="form-control form-control-sm" placeholder="Nama pemilik">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small">Logo Bank <span class="text-muted">(opsional)</span></label>
                <div class="d-flex align-items-center gap-2">
                    <div id="logoPreview__IDX__"></div>
                    <input type="file" name="logo[__IDX__]" class="form-control form-control-sm"
                           accept="image/*" style="max-width:260px;"
                           onchange="previewLogo(this, __IDX__)">
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
let bankIndex = {{ count($accounts) }};

// ── Add bank row ──
document.getElementById('addBank').addEventListener('click', () => {
    const tpl = document.getElementById('bankRowTemplate').innerHTML
        .replaceAll('__IDX__', bankIndex)
        .replaceAll('__NUM__', bankIndex + 1);
    document.getElementById('bankList').insertAdjacentHTML('beforeend', tpl);
    bankIndex++;
    updateRemoveButtons();
    updatePreview();
});

// ── Remove bank row ──
document.getElementById('bankList').addEventListener('click', e => {
    if (e.target.closest('.remove-bank')) {
        e.target.closest('.bank-row').remove();
        renumberRows();
        updateRemoveButtons();
        updatePreview();
    }
});

function renumberRows() {
    document.querySelectorAll('.bank-row').forEach((row, i) => {
        row.querySelector('.row-num').textContent = i + 1;
    });
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.bank-row');
    rows.forEach(r => {
        const btn = r.querySelector('.remove-bank');
        if (btn) btn.style.display = rows.length <= 1 ? 'none' : '';
    });
}

// ── Logo preview ──
function previewLogo(input, idx) {
    const wrap = document.getElementById('logoPreview' + idx);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            wrap.innerHTML = `<img src="${e.target.result}" style="max-height:40px;max-width:80px;" class="img-thumbnail">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo(idx) {
    document.getElementById('logoPreview' + idx).innerHTML = '';
    const hidden = document.getElementById('deleteLogo' + idx);
    if (hidden) hidden.value = '1';
}

// ── Live preview ──
function updatePreview() {
    const rows = document.querySelectorAll('.bank-row');
    let html = '';
    rows.forEach(row => {
        const bank   = row.querySelector('[name$="[bank_name]"]')?.value || '';
        const num    = row.querySelector('[name$="[account_number]"]')?.value || '';
        const name   = row.querySelector('[name$="[account_name]"]')?.value || '';
        if (!bank && !num) return;
        html += `<div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;margin-bottom:8px;">
            <div class="fw-semibold small">${bank || '—'}</div>
            <div class="small"><span class="text-muted">No:</span> <strong>${num || '—'}</strong></div>
            <div class="small text-muted">a.n. ${name || '—'}</div>
        </div>`;
    });
    document.getElementById('previewBanks').innerHTML = html || '<p class="text-muted small">Belum ada rekening</p>';

    const inst = document.querySelector('[name=payment_instructions]')?.value || '';
    document.getElementById('previewInstructions').textContent = inst;
}

document.getElementById('bankList').addEventListener('input', updatePreview);
document.querySelector('[name=payment_instructions]').addEventListener('input', updatePreview);
updatePreview();
</script>
@endpush
