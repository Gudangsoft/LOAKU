@extends('publisher.layout')

@section('title', 'Paket Langganan Saya')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold"><i class="fas fa-box-open me-2 text-primary"></i>Paket Langganan Saya</h4>
    <p class="text-muted">Kelola dan pantau status langganan publisher Anda.</p>
</div>

{{-- Status Langganan Aktif --}}
@if($activeSubscription)
    @php
        $plan = $activeSubscription->plan;
        $daysLeft = $activeSubscription->daysRemaining();
        $alertClass = $daysLeft <= 7 ? 'warning' : ($daysLeft <= 30 ? 'info' : 'success');
    @endphp
    <div class="card mb-4" style="border-left: 4px solid {{ $daysLeft <= 7 ? '#ffc107' : '#1cc88a' }}">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">AKTIF</span>
                        <h5 class="mb-0 fw-bold">{{ $plan->name }}</h5>
                    </div>
                    <div class="row text-muted small mt-2">
                        <div class="col-6 col-md-3 mb-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <strong>Mulai:</strong> {{ $activeSubscription->start_date->format('d/m/Y') }}
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <i class="fas fa-calendar-times me-1"></i>
                            <strong>Berakhir:</strong> {{ $activeSubscription->end_date->format('d/m/Y') }}
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <i class="fas fa-clock me-1"></i>
                            <strong class="{{ $daysLeft <= 7 ? 'text-danger' : ($daysLeft <= 30 ? 'text-warning' : 'text-success') }}">
                                {{ $daysLeft }} hari tersisa
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="text-muted small">Harga paket</div>
                    <h4 class="fw-bold text-primary mb-0">{{ $plan->formattedPrice() }}</h4>
                    <small class="text-muted">per {{ $plan->duration_months }} bulan</small>
                </div>
            </div>

            @if($daysLeft <= 30)
            <div class="alert alert-{{ $alertClass }} mt-3 mb-0 py-2 small">
                <i class="fas fa-exclamation-triangle me-1"></i>
                @if($daysLeft <= 7)
                    Langganan Anda akan berakhir dalam <strong>{{ $daysLeft }} hari</strong>. Hubungi admin untuk perpanjangan.
                @else
                    Langganan Anda akan berakhir dalam <strong>{{ $daysLeft }} hari</strong>.
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Penggunaan --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Jurnal Terdaftar</div>
                            <h3 class="fw-bold mb-0">{{ $journalCount }}</h3>
                            <small class="text-muted">dari {{ $plan->maxJournalsLabel() }} maksimal</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-book fa-lg text-primary"></i>
                        </div>
                    </div>
                    @if($plan->max_journals !== null)
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            @php $pct = min(100, ($journalCount / $plan->max_journals) * 100); @endphp
                            <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success') }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block">{{ $plan->max_journals - $journalCount }} slot tersisa</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">LOA Bulan Ini</div>
                            <h3 class="fw-bold mb-0">{{ $loaThisMonth }}</h3>
                            <small class="text-muted">dari {{ $plan->maxLoaPerMonthLabel() }} per bulan</small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-file-alt fa-lg text-info"></i>
                        </div>
                    </div>
                    @if($plan->max_loa_per_month !== null)
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            @php $pctLoa = min(100, ($loaThisMonth / $plan->max_loa_per_month) * 100); @endphp
                            <div class="progress-bar {{ $pctLoa >= 90 ? 'bg-danger' : ($pctLoa >= 70 ? 'bg-warning' : 'bg-info') }}"
                                 style="width: {{ $pctLoa }}%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block">{{ $plan->max_loa_per_month - $loaThisMonth }} LOA tersisa bulan ini</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card mb-4 border-warning">
        <div class="card-body text-center py-5">
            <i class="fas fa-box-open fa-3x text-warning mb-3"></i>
            <h5 class="fw-bold">Tidak Ada Langganan Aktif</h5>
            <p class="text-muted">Anda belum memiliki paket langganan aktif. Hubungi admin untuk berlangganan.</p>
        </div>
    </div>
@endif

{{-- Daftar Paket Tersedia --}}
@if($plans->count() > 0)
<h5 class="fw-bold mb-3 mt-2">Paket Tersedia</h5>
<div class="row mb-4">
    @foreach($plans as $plan)
    <div class="col-md-4 mb-3">
        <div class="card h-100 {{ $activeSubscription && $activeSubscription->subscription_plan_id === $plan->id ? 'border-primary' : '' }}">
            @if($activeSubscription && $activeSubscription->subscription_plan_id === $plan->id)
                <div class="card-header bg-primary text-white text-center py-2 small fw-bold">
                    <i class="fas fa-check-circle me-1"></i>PAKET ANDA SAAT INI
                </div>
            @endif
            <div class="card-body text-center">
                <h5 class="fw-bold">{{ $plan->name }}</h5>
                <h3 class="text-primary fw-bold my-2">{{ $plan->formattedPrice() }}</h3>
                <div class="text-muted small mb-3">per {{ $plan->duration_months }} bulan</div>

                @if($plan->description)
                    <p class="text-muted small">{{ $plan->description }}</p>
                @endif

                <hr>
                <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="fas fa-book text-success me-2"></i>Jurnal: <strong>{{ $plan->maxJournalsLabel() }}</strong></li>
                    <li class="mb-2"><i class="fas fa-file-alt text-info me-2"></i>LOA/bulan: <strong>{{ $plan->maxLoaPerMonthLabel() }}</strong></li>
                    <li><i class="fas fa-calendar text-warning me-2"></i>Durasi: <strong>{{ $plan->duration_months }} bulan</strong></li>
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>
<p class="text-muted small">
    <i class="fas fa-info-circle me-1"></i>
    Untuk berlangganan atau perpanjang paket, silakan hubungi administrator sistem.
</p>
@endif

{{-- Riwayat Langganan --}}
@if($history->count() > 0)
<h5 class="fw-bold mb-3 mt-4">Riwayat Langganan</h5>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Paket</th>
                    <th>Mulai</th>
                    <th>Berakhir</th>
                    <th>Status</th>
                    <th>Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $sub)
                @php
                    $isExpired = $sub->end_date->isPast();
                    $effStatus = ($sub->status === 'active' && $isExpired) ? 'expired' : $sub->status;
                @endphp
                <tr>
                    <td><strong>{{ $sub->plan->name }}</strong></td>
                    <td>{{ $sub->start_date->format('d/m/Y') }}</td>
                    <td>{{ $sub->end_date->format('d/m/Y') }}</td>
                    <td>
                        @if($effStatus === 'active')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($effStatus === 'expired')
                            <span class="badge bg-secondary">Kadaluarsa</span>
                        @else
                            <span class="badge bg-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        @if($sub->amount_paid > 0)
                            Rp {{ number_format($sub->amount_paid, 0, ',', '.') }}
                        @else
                            <span class="text-muted">Gratis</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
