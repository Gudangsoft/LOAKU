@extends('layouts.admin')

@section('title', 'Langganan Publisher')

@section('content')
<div class="container-fluid p-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-id-card me-2 text-primary"></i>Langganan Publisher
        </h1>
        <a href="{{ route('admin.publisher-subscriptions.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm me-1"></i>Tambah Langganan
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

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Cari nama publisher..." value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="plan_id" class="form-select form-select-sm">
                        <option value="">Semua Paket</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
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
                            <th>#</th>
                            <th>Publisher</th>
                            <th>Paket</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Status</th>
                            <th>Dibayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                        @php
                            $isExpired = $sub->end_date->isPast();
                            $effectiveStatus = ($sub->status === 'active' && $isExpired) ? 'expired' : $sub->status;
                        @endphp
                        <tr>
                            <td>{{ $subscriptions->firstItem() + $loop->index }}</td>
                            <td>
                                <strong>{{ $sub->publisher->name }}</strong>
                            </td>
                            <td>{{ $sub->plan->name }}</td>
                            <td>{{ $sub->start_date->format('d/m/Y') }}</td>
                            <td>
                                {{ $sub->end_date->format('d/m/Y') }}
                                @if($effectiveStatus === 'active' && !$isExpired)
                                    <br><small class="text-success">{{ $sub->daysRemaining() }} hari lagi</small>
                                @endif
                            </td>
                            <td>
                                @if($effectiveStatus === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($effectiveStatus === 'expired')
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
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.publisher-subscriptions.show', $sub) }}"
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.publisher-subscriptions.edit', $sub) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($sub->status === 'active' && !$isExpired)
                                    <form action="{{ route('admin.publisher-subscriptions.cancel', $sub) }}" method="POST"
                                          onsubmit="return confirm('Batalkan langganan ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" title="Batalkan">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada data langganan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($subscriptions->hasPages())
        <div class="card-footer">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
