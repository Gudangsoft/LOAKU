@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.subscription-payments.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-check-alt me-2 text-primary"></i>
            Detail Pembayaran — {{ $subscriptionPayment->invoice_number }}
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Detail Kiri --}}
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Invoice</h6>
                    <span class="badge bg-{{ $subscriptionPayment->statusBadge() }} fs-6">
                        {{ $subscriptionPayment->statusLabel() }}
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><td class="fw-semibold" width="40%">No. Invoice</td><td class="font-monospace">{{ $subscriptionPayment->invoice_number }}</td></tr>
                        <tr><td class="fw-semibold">Publisher</td><td>{{ $subscriptionPayment->publisher->name }}</td></tr>
                        <tr><td class="fw-semibold">Paket</td><td>{{ $subscriptionPayment->plan->name }}</td></tr>
                        <tr><td class="fw-semibold">Nominal</td><td class="fw-bold text-primary">Rp {{ number_format($subscriptionPayment->amount, 0, ',', '.') }}</td></tr>
                        <tr><td class="fw-semibold">Tgl. Invoice</td><td>{{ $subscriptionPayment->created_at->format('d F Y H:i') }}</td></tr>
                        @if($subscriptionPayment->confirmed_at)
                        <tr><td class="fw-semibold">Tgl. Proses</td><td>{{ $subscriptionPayment->confirmed_at->format('d F Y H:i') }}</td></tr>
                        <tr><td class="fw-semibold">Diproses oleh</td><td>{{ $subscriptionPayment->confirmedBy?->name ?? '-' }}</td></tr>
                        @endif
                        @if($subscriptionPayment->admin_notes)
                        <tr><td class="fw-semibold">Catatan</td><td>{{ $subscriptionPayment->admin_notes }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Bukti Bayar --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bukti Pembayaran</h6>
                </div>
                <div class="card-body text-center">
                    @if($subscriptionPayment->payment_proof)
                        @php $ext = pathinfo($subscriptionPayment->payment_proof, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                            <img src="{{ Storage::url($subscriptionPayment->payment_proof) }}"
                                 alt="Bukti Bayar" class="img-fluid rounded shadow" style="max-height:400px;">
                            <div class="mt-2">
                                <a href="{{ Storage::url($subscriptionPayment->payment_proof) }}"
                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>Buka Full
                                </a>
                            </div>
                        @elseif(strtolower($ext) === 'pdf')
                            <div class="alert alert-info">
                                <i class="fas fa-file-pdf fa-2x d-block mb-2"></i>
                                File PDF
                                <a href="{{ Storage::url($subscriptionPayment->payment_proof) }}"
                                   target="_blank" class="btn btn-primary btn-sm ms-2">
                                    <i class="fas fa-download me-1"></i>Download PDF
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="py-4 text-muted">
                            <i class="fas fa-image fa-3x mb-2 d-block"></i>
                            Belum ada bukti pembayaran diupload.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Aksi Kanan --}}
        <div class="col-md-5">
            @if(in_array($subscriptionPayment->status, ['proof_uploaded', 'pending_payment']))
            <div class="card shadow mb-4 border-success">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-check me-2"></i>Konfirmasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-payments.confirm', $subscriptionPayment) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" class="form-control"
                                      placeholder="Catatan konfirmasi..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Konfirmasi pembayaran ini? Langganan akan langsung aktif.')">
                            <i class="fas fa-check-circle me-1"></i>Konfirmasi & Aktifkan Langganan
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4 border-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-times me-2"></i>Tolak Pembayaran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-payments.reject', $subscriptionPayment) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="notes" rows="3" class="form-control" required
                                      placeholder="Misal: Nominal tidak sesuai, bukti tidak terbaca, dll."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Tolak pembayaran ini?')">
                            <i class="fas fa-times-circle me-1"></i>Tolak Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Detail Paket</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr><td class="fw-semibold">Paket</td><td>{{ $subscriptionPayment->plan->name }}</td></tr>
                        <tr><td class="fw-semibold">Harga</td><td>{{ $subscriptionPayment->plan->formattedPrice() }}</td></tr>
                        <tr><td class="fw-semibold">Durasi</td><td>{{ $subscriptionPayment->plan->duration_months }} bulan</td></tr>
                        <tr><td class="fw-semibold">Maks. Jurnal</td><td>{{ $subscriptionPayment->plan->maxJournalsLabel() }}</td></tr>
                        <tr><td class="fw-semibold">Maks. LOA/bln</td><td>{{ $subscriptionPayment->plan->maxLoaPerMonthLabel() }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
