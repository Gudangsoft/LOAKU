@extends('layouts.admin')

@section('title', 'Tambah Paket Langganan')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2 text-primary"></i>Tambah Paket Langganan
        </h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-body p-4">
                    <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="mis. Basic, Standard, Premium" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Deskripsi singkat paket...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', 0) }}" min="0" step="1000" required>
                                <div class="form-text">Isi 0 untuk gratis</div>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Durasi (bulan) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror"
                                       value="{{ old('duration_months', 1) }}" min="1" required>
                                @error('duration_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Maks. Jurnal</label>
                                <input type="number" name="max_journals" class="form-control @error('max_journals') is-invalid @enderror"
                                       value="{{ old('max_journals') }}" min="1" placeholder="Kosongkan = tidak terbatas">
                                @error('max_journals') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Maks. LOA/bulan</label>
                                <input type="number" name="max_loa_per_month" class="form-control @error('max_loa_per_month') is-invalid @enderror"
                                       value="{{ old('max_loa_per_month') }}" min="1" placeholder="Kosongkan = tidak terbatas">
                                @error('max_loa_per_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Urutan Tampil</label>
                                <input type="number" name="sort_order" class="form-control"
                                       value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                           value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Paket
                            </button>
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
