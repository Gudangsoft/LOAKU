@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="container-fluid p-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-check-alt me-2 text-primary"></i>Konfirmasi Pembayaran
        </h1>
        <a href="{{ route('admin.payment-settings.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-university me-1"></i>Pengaturan Rekening
        </a>
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

    {{-- Statistik --}}
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card border-warning shadow-sm text-center py-3">
                <div class="h4 fw-bold text-warning mb-0">{{ $stats['pending'] }}</div>
                <div class="small text-muted">Menunggu Bayar</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-info shadow-sm text-center py-3">
                <div class="h4 fw-bold text-info mb-0">{{ $stats['uploaded'] }}</div>
                <div class="small text-muted">Bukti Terkirim</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-success shadow-sm text-center py-3">
                <div class="h4 fw-bold text-success mb-0">{{ $stats['confirmed'] }}</div>
                <div class="small text-muted">Dikonfirmasi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-danger shadow-sm text-center py-3">
                <div class="h4 fw-bold text-danger mb-0">{{ $stats['rejected'] }}</div>
                <div class="small text-muted">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Cari nama publisher / no. invoice..." value="{{ request('q') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending_payment"  {{ request('status') === 'pending_payment'  ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="proof_uploaded"   {{ request('status') === 'proof_uploaded'   ? 'selected' : '' }}>Bukti Dikirim</option>
                        <option value="confirmed"        {{ request('status') === 'confirmed'        ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="rejected"         {{ request('status') === 'rejected'         ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Publisher</th>
                            <th>Paket</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tgl. Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><span class="font-monospace">{{ $payment->invoice_number }}</span></td>
                            <td><strong>{{ $payment->publisher->name }}</strong></td>
                            <td>{{ $payment->plan->name }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->statusBadge() }}">
                                    {{ $payment->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.subscription-payments.show', $payment) }}"
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>Belum ada data pembayaran.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
        <div class="card-footer">{{ $payments->links() }}</div>
        @endif
    </div>
</div>
@endsection
