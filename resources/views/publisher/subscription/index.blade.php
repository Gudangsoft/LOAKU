@extends('publisher.layout')

@section('title', 'Paket Langganan')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold"><i class="fas fa-box-open me-2 text-primary"></i>Paket Langganan Saya</h4>
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

{{-- ══ INVOICE / TAGIHAN AKTIF ══ --}}
@if($latestPayment && in_array($latestPayment->status, ['pending_payment', 'proof_uploaded', 'rejected']))
<div class="card mb-4 border-{{ $latestPayment->statusBadge() }}">
    <div class="card-header bg-{{ $latestPayment->statusBadge() }} text-white d-flex justify-content-between align-items-center py-2">
        <span class="fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i>Tagihan: {{ $latestPayment->invoice_number }}</span>
        <span class="badge bg-white text-{{ $latestPayment->statusBadge() }}">{{ $latestPayment->statusLabel() }}</span>
    </div>
    <div class="card-body">
        @php
            // Load multi-bank accounts
            $accountsJson = \App\Models\WebsiteSetting::getValue('payment_bank_accounts', '');
            $bankAccounts = $accountsJson ? (json_decode($accountsJson, true) ?? []) : [];
            // Fallback legacy
            if (empty($bankAccounts)) {
                $legacyBank = \App\Models\WebsiteSetting::getValue('payment_bank_name', '');
                $legacyNum  = \App\Models\WebsiteSetting::getValue('payment_bank_account_number', '');
                $legacyName = \App\Models\WebsiteSetting::getValue('payment_bank_account_name', '');
                $legacyLogo = \App\Models\WebsiteSetting::getValue('payment_bank_logo', '');
                if ($legacyBank || $legacyNum) {
                    $bankAccounts = [['bank_name'=>$legacyBank,'account_number'=>$legacyNum,'account_name'=>$legacyName,'logo'=>$legacyLogo]];
                }
            }
            $whatsapp    = \App\Models\WebsiteSetting::getValue('payment_whatsapp', '');
            $waTemplate  = \App\Models\WebsiteSetting::getValue('payment_whatsapp_message',
                'Halo Admin, saya ingin konfirmasi pembayaran. No. Invoice: {invoice}');
            $instructions = \App\Models\WebsiteSetting::getValue('payment_instructions');
            // Build WA URL
            $waMsg = str_replace(
                ['{invoice}', '{plan}', '{amount}', '{name}'],
                [$latestPayment->invoice_number, $latestPayment->plan->name,
                 'Rp '.number_format($latestPayment->amount,0,',','.'), auth()->user()->name],
                $waTemplate
            );
        @endphp

        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Detail Invoice</h6>
                <table class="table table-sm table-borderless">
                    <tr><td class="fw-semibold" width="45%">Paket</td><td>{{ $latestPayment->plan->name }}</td></tr>
                    <tr><td class="fw-semibold">Nominal</td><td class="fw-bold text-primary">Rp {{ number_format($latestPayment->amount, 0, ',', '.') }}</td></tr>
                    <tr><td class="fw-semibold">Tanggal</td><td>{{ $latestPayment->created_at->format('d F Y') }}</td></tr>
                </table>

                @if($latestPayment->status === 'rejected' && $latestPayment->admin_notes)
                <div class="alert alert-danger py-2 small">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <strong>Alasan ditolak:</strong> {{ $latestPayment->admin_notes }}
                </div>
                @endif

                {{-- Tombol WhatsApp --}}
                @if($whatsapp)
                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($waMsg) }}"
                   target="_blank" class="btn btn-success w-100 mt-2">
                    <i class="fab fa-whatsapp me-2"></i>Konfirmasi via WhatsApp
                </a>
                @endif
            </div>

            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Informasi Rekening</h6>

                @forelse($bankAccounts as $acc)
                @if(empty($acc['bank_name']) && empty($acc['account_number'])) @continue @endif
                <div class="bg-light rounded p-3 mb-2">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        @if(!empty($acc['logo']))
                            <img src="{{ Storage::url($acc['logo']) }}" style="max-height:28px;max-width:60px;" alt="">
                        @endif
                        <strong class="text-primary">{{ $acc['bank_name'] ?? '' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">No. Rekening</span>
                        <div class="d-flex align-items-center gap-2">
                            <strong>{{ $acc['account_number'] ?? '' }}</strong>
                            <button class="btn btn-sm btn-outline-secondary py-0 px-1"
                                    onclick="copyText('{{ $acc['account_number'] ?? '' }}')" title="Salin">
                                <i class="fas fa-copy fa-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Atas Nama</span>
                        <span>{{ $acc['account_name'] ?? '' }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted small">Informasi rekening belum diatur oleh admin.</p>
                @endforelse

                @if($instructions)
                    <p class="text-muted small mt-2">{{ $instructions }}</p>
                @endif
            </div>
        </div>

        {{-- Upload Bukti --}}
        <hr>
        <h6 class="fw-bold mb-3">
            @if($latestPayment->status === 'rejected') Upload Ulang Bukti Pembayaran
            @else Upload Bukti Pembayaran
            @endif
        </h6>

        @if($latestPayment->payment_proof && $latestPayment->status === 'proof_uploaded')
            <div class="alert alert-info py-2 small mb-3">
                <i class="fas fa-clock me-1"></i>Bukti pembayaran sudah dikirim. Menunggu konfirmasi dari admin.
            </div>
        @endif

        <form action="{{ route('publisher.subscription.upload-proof', $latestPayment) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row align-items-end g-3">
                <div class="col-md-7">
                    <label class="form-label fw-semibold small">File Bukti Transfer (JPG/PNG/PDF, maks. 3MB)</label>
                    <input type="file" name="payment_proof" class="form-control form-control-sm @error('payment_proof') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.pdf" required>
                    @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload me-1"></i>Upload Bukti Bayar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ══ LANGGANAN AKTIF ══ --}}
@if($activeSubscription)
    @php
        $plan    = $activeSubscription->plan;
        $daysLeft = $activeSubscription->daysRemaining();
    @endphp
    <div class="card mb-4" style="border-left:4px solid #1cc88a;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge bg-success me-2">AKTIF</span>
                    <strong class="fs-5">{{ $plan->name }}</strong>
                    <div class="text-muted small mt-1">
                        {{ $activeSubscription->start_date->format('d/m/Y') }} — {{ $activeSubscription->end_date->format('d/m/Y') }}
                        • <span class="{{ $daysLeft <= 7 ? 'text-danger fw-bold' : ($daysLeft <= 30 ? 'text-warning fw-bold' : 'text-success') }}">{{ $daysLeft }} hari tersisa</span>
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-primary fw-bold fs-5">{{ $plan->formattedPrice() }}</div>
                    <small class="text-muted">/ {{ $plan->duration_months }} bulan</small>
                </div>
            </div>

            <div class="row mt-3 g-3">
                <div class="col-md-6">
                    <div class="bg-light rounded p-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">Jurnal Terdaftar</span>
                            <strong>{{ $journalCount }} / {{ $plan->maxJournalsLabel() }}</strong>
                        </div>
                        @if($plan->max_journals)
                        <div class="progress" style="height:5px;">
                            @php $pj = min(100, ($journalCount / $plan->max_journals) * 100); @endphp
                            <div class="progress-bar {{ $pj >= 90 ? 'bg-danger' : 'bg-success' }}" style="width:{{ $pj }}%"></div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light rounded p-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">LOA Bulan Ini</span>
                            <strong>{{ $loaThisMonth }} / {{ $plan->maxLoaPerMonthLabel() }}</strong>
                        </div>
                        @if($plan->max_loa_per_month)
                        <div class="progress" style="height:5px;">
                            @php $pl = min(100, ($loaThisMonth / $plan->max_loa_per_month) * 100); @endphp
                            <div class="progress-bar {{ $pl >= 90 ? 'bg-danger' : 'bg-info' }}" style="width:{{ $pl }}%"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($daysLeft <= 30 && !($latestPayment && in_array($latestPayment->status, ['pending_payment','proof_uploaded'])))
            <div class="alert alert-warning mt-3 mb-0 py-2 small">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Langganan akan berakhir dalam <strong>{{ $daysLeft }} hari</strong>. Pilih paket di bawah untuk perpanjang.
            </div>
            @endif
        </div>
    </div>
@elseif(!$latestPayment || $latestPayment->status === 'rejected')
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Anda belum memiliki langganan aktif. Pilih paket di bawah untuk mulai berlangganan.
    </div>
@endif

{{-- ══ PILIH / UPGRADE PAKET ══ --}}
@if(!($latestPayment && in_array($latestPayment->status, ['pending_payment','proof_uploaded'])))
<h5 class="fw-bold mb-3 mt-2">
    {{ $activeSubscription ? 'Upgrade / Perpanjang Paket' : 'Pilih Paket' }}
</h5>
<div class="row g-3 mb-4">
    @foreach($plans as $plan)
    @php $isCurrent = $activeSubscription && $activeSubscription->subscription_plan_id === $plan->id; @endphp
    <div class="col-md-4">
        <div class="card h-100 {{ $isCurrent ? 'border-primary' : '' }}">
            @if($isCurrent)
            <div class="card-header bg-primary text-white text-center py-1 small fw-bold">PAKET SAAT INI</div>
            @endif
            <div class="card-body text-center">
                <h5 class="fw-bold">{{ $plan->name }}</h5>
                <h3 class="text-primary fw-bold">{{ $plan->formattedPrice() }}</h3>
                <small class="text-muted">/ {{ $plan->duration_months }} bulan</small>
                @if($plan->description)<p class="text-muted small mt-2">{{ $plan->description }}</p>@endif
                <hr>
                <ul class="list-unstyled text-start small">
                    <li class="mb-1"><i class="fas fa-book text-success me-2"></i>Jurnal: <strong>{{ $plan->maxJournalsLabel() }}</strong></li>
                    <li class="mb-1"><i class="fas fa-file-alt text-info me-2"></i>LOA/bulan: <strong>{{ $plan->maxLoaPerMonthLabel() }}</strong></li>
                    <li><i class="fas fa-calendar text-warning me-2"></i>Durasi: <strong>{{ $plan->duration_months }} bulan</strong></li>
                </ul>
            </div>
            <div class="card-footer bg-transparent">
                <form action="{{ route('publisher.subscription.select-plan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit" class="btn {{ $isCurrent ? 'btn-outline-primary' : 'btn-primary' }} w-100"
                            onclick="return confirm('Buat invoice untuk paket {{ $plan->name }}?')">
                        <i class="fas fa-{{ $isCurrent ? 'redo' : 'bolt' }} me-1"></i>
                        {{ $isCurrent ? 'Perpanjang' : 'Pilih Paket' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
<p class="text-muted small"><i class="fas fa-info-circle me-1"></i>Setelah memilih paket, invoice akan dibuat dan detail pembayaran akan dikirim ke email Anda.</p>
@endif

{{-- ══ RIWAYAT PEMBAYARAN ══ --}}
@if($paymentHistory->count() > 0)
<h5 class="fw-bold mb-3 mt-4">Riwayat Pembayaran</h5>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Invoice</th>
                    <th>Paket</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentHistory as $pay)
                <tr>
                    <td class="font-monospace small">{{ $pay->invoice_number }}</td>
                    <td>{{ $pay->plan->name }}</td>
                    <td>Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $pay->statusBadge() }}">{{ $pay->statusLabel() }}</span></td>
                    <td>{{ $pay->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Nomor rekening disalin!');
    });
}
</script>
@endpush
