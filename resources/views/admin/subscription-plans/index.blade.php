@extends('layouts.admin')

@section('title', 'Paket Langganan')

@section('content')
<div class="container-fluid p-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box-open me-2 text-primary"></i>Paket Langganan
        </h1>
        <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm me-1"></i>Tambah Paket
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($plans as $plan)
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 {{ $plan->is_active ? '' : 'border-secondary opacity-75' }}">
                <div class="card-header py-3 {{ $plan->is_active ? 'bg-primary' : 'bg-secondary' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-white">{{ $plan->name }}</h6>
                        @if(!$plan->is_active)
                            <span class="badge bg-warning text-dark">Nonaktif</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="text-primary fw-bold">{{ $plan->formattedPrice() }}</h2>
                        <small class="text-muted">per {{ $plan->duration_months }} bulan</small>
                    </div>

                    @if($plan->description)
                        <p class="text-muted small mb-3">{{ $plan->description }}</p>
                    @endif

                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-book text-success me-2"></i>
                            <strong>Jurnal:</strong> {{ $plan->maxJournalsLabel() }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-file-alt text-info me-2"></i>
                            <strong>LOA/bulan:</strong> {{ $plan->maxLoaPerMonthLabel() }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar text-warning me-2"></i>
                            <strong>Durasi:</strong> {{ $plan->duration_months }} bulan
                        </li>
                    </ul>

                    <div class="text-muted small mt-3">
                        <i class="fas fa-users me-1"></i>
                        {{ $plan->subscriptions()->where('status', 'active')->count() }} publisher aktif
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="btn btn-warning btn-sm flex-fill">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST"
                          onsubmit="return confirm('Hapus paket ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada paket langganan. Buat paket pertama Anda.</p>
                    <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Paket
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
