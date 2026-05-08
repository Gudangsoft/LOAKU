@extends('layouts.admin')

@section('title', 'Edit Langganan')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.publisher-subscriptions.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2 text-warning"></i>Edit Langganan: {{ $publisherSubscription->publisher->name }}
        </h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <form action="{{ route('admin.publisher-subscriptions.update', $publisherSubscription) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Paket Langganan <span class="text-danger">*</span></label>
                            <select name="subscription_plan_id" class="form-select @error('subscription_plan_id') is-invalid @enderror" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        {{ old('subscription_plan_id', $publisherSubscription->subscription_plan_id) == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} — {{ $plan->formattedPrice() }} / {{ $plan->duration_months }} bln
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', $publisherSubscription->start_date->toDateString()) }}" required>
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Berakhir <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', $publisherSubscription->end_date->toDateString()) }}" required>
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', $publisherSubscription->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="expired" {{ old('status', $publisherSubscription->status) === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                    <option value="cancelled" {{ old('status', $publisherSubscription->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jumlah Dibayar (Rp)</label>
                                <input type="number" name="amount_paid" class="form-control @error('amount_paid') is-invalid @enderror"
                                       value="{{ old('amount_paid', $publisherSubscription->amount_paid) }}" min="0" step="1000">
                                @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="notes" rows="2" class="form-control">{{ old('notes', $publisherSubscription->notes) }}</textarea>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.publisher-subscriptions.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
