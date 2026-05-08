@extends('layouts.admin')

@section('title', 'Detail Langganan')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.publisher-subscriptions.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-id-card me-2 text-primary"></i>Detail Langganan
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $isExpired = $publisherSubscription->end_date->isPast();
        $effectiveStatus = ($publisherSubscription->status === 'active' && $isExpired) ? 'expired' : $publisherSubscription->status;
    @endphp

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Langganan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-semibold" width="40%">Publisher</td>
                            <td>{{ $publisherSubscription->publisher->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Paket</td>
                            <td>{{ $publisherSubscription->plan->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Status</td>
                            <td>
                                @if($effectiveStatus === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                    <small class="text-success ms-2">{{ $publisherSubscription->daysRemaining() }} hari tersisa</small>
                                @elseif($effectiveStatus === 'expired')
                                    <span class="badge bg-secondary">Kadaluarsa</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Mulai</td>
                            <td>{{ $publisherSubscription->start_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Berakhir</td>
                            <td>{{ $publisherSubscription->end_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Dibayar</td>
                            <td>
                                @if($publisherSubscription->amount_paid > 0)
                                    Rp {{ number_format($publisherSubscription->amount_paid, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">Gratis</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Dibuat oleh</td>
                            <td>{{ $publisherSubscription->creator?->name ?? '-' }}</td>
                        </tr>
                        @if($publisherSubscription->notes)
                        <tr>
                            <td class="fw-semibold">Catatan</td>
                            <td>{{ $publisherSubscription->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Paket</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-semibold" width="50%">Harga Paket</td>
                            <td>{{ $publisherSubscription->plan->formattedPrice() }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Maks. Jurnal</td>
                            <td>{{ $publisherSubscription->plan->maxJournalsLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Maks. LOA/bulan</td>
                            <td>{{ $publisherSubscription->plan->maxLoaPerMonthLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Durasi</td>
                            <td>{{ $publisherSubscription->plan->duration_months }} bulan</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.publisher-subscriptions.edit', $publisherSubscription) }}"
                   class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                @if($effectiveStatus === 'active')
                <form action="{{ route('admin.publisher-subscriptions.cancel', $publisherSubscription) }}" method="POST"
                      onsubmit="return confirm('Batalkan langganan ini?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i>Batalkan Langganan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
