@extends('layouts.admin')

@section('title', 'Tambah Langganan Publisher')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.publisher-subscriptions.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2 text-primary"></i>Tambah Langganan Publisher
        </h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <form action="{{ route('admin.publisher-subscriptions.store') }}" method="POST" id="subForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Publisher <span class="text-danger">*</span></label>
                            <select name="publisher_id" class="form-select @error('publisher_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Publisher --</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                        {{ (old('publisher_id', $selectedPublisher?->id) == $publisher->id) ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('publisher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Paket Langganan <span class="text-danger">*</span></label>
                            <select name="subscription_plan_id" class="form-select @error('subscription_plan_id') is-invalid @enderror"
                                    id="planSelect" required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        data-price="{{ $plan->price }}"
                                        data-duration="{{ $plan->duration_months }}"
                                        {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} — {{ $plan->formattedPrice() }} / {{ $plan->duration_months }} bln
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="startDate"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', now()->toDateString()) }}" required>
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Berakhir (otomatis)</label>
                                <input type="text" id="endDatePreview" class="form-control" readonly
                                       placeholder="Pilih paket & tanggal mulai">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Dibayar (Rp)</label>
                            <input type="number" name="amount_paid" id="amountPaid"
                                   class="form-control @error('amount_paid') is-invalid @enderror"
                                   value="{{ old('amount_paid', 0) }}" min="0" step="1000">
                            <div class="form-text">Biarkan 0 jika gratis atau belum dibayar.</div>
                            @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Langganan
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

@push('scripts')
<script>
function updateEndDate() {
    const planEl = document.getElementById('planSelect');
    const startEl = document.getElementById('startDate');
    const endEl = document.getElementById('endDatePreview');
    const amountEl = document.getElementById('amountPaid');

    const selected = planEl.options[planEl.selectedIndex];
    const duration = parseInt(selected.dataset.duration);
    const price = parseFloat(selected.dataset.price);
    const startVal = startEl.value;

    if (duration && startVal) {
        const start = new Date(startVal);
        const end = new Date(start);
        end.setMonth(end.getMonth() + duration);
        end.setDate(end.getDate() - 1);
        endEl.value = end.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
    } else {
        endEl.value = '';
    }

    if (!isNaN(price)) {
        amountEl.value = price;
    }
}

document.getElementById('planSelect').addEventListener('change', updateEndDate);
document.getElementById('startDate').addEventListener('change', updateEndDate);
</script>
@endpush
