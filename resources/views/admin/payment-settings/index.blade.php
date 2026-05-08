@extends('layouts.admin')

@section('title', 'Pengaturan Rekening Pembayaran')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-university me-2 text-primary"></i>Pengaturan Rekening Pembayaran
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-credit-card me-2"></i>Info Rekening Bank
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.payment-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Bank</label>
                            <input type="text" name="payment_bank_name" class="form-control"
                                   value="{{ $settings['payment_bank_name'] }}"
                                   placeholder="contoh: Bank BCA, Bank Mandiri">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Rekening</label>
                            <input type="text" name="payment_bank_account_number" class="form-control"
                                   value="{{ $settings['payment_bank_account_number'] }}"
                                   placeholder="1234567890">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Atas Nama</label>
                            <input type="text" name="payment_bank_account_name" class="form-control"
                                   value="{{ $settings['payment_bank_account_name'] }}"
                                   placeholder="Nama pemilik rekening">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Logo Bank</label>
                            @if($settings['payment_bank_logo'])
                                <div class="mb-2">
                                    <img src="{{ Storage::url($settings['payment_bank_logo']) }}"
                                         alt="Logo Bank" style="max-height:60px;" class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" name="payment_bank_logo" class="form-control"
                                   accept="image/png,image/jpg,image/jpeg,image/svg+xml">
                            <div class="form-text">PNG/JPG/SVG, maks. 1 MB</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Instruksi Pembayaran</label>
                            <textarea name="payment_instructions" rows="5" class="form-control"
                                      placeholder="Tulis panduan pembayaran untuk publisher, mis: Transfer ke rekening di atas, lalu upload bukti transfer di dashboard publisher Anda.">{{ $settings['payment_instructions'] }}</textarea>
                            <div class="form-text">Teks ini akan ditampilkan di email invoice dan halaman pembayaran publisher.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-eye me-2"></i>Preview di Email Publisher
                    </h6>
                </div>
                <div class="card-body" style="background:#f8f9fa;">
                    <div class="border rounded p-3 bg-white">
                        <p class="small text-muted mb-2">Informasi Pembayaran:</p>
                        @if($settings['payment_bank_logo'])
                            <img src="{{ Storage::url($settings['payment_bank_logo']) }}" style="max-height:40px;" class="mb-2 d-block">
                        @endif
                        <table class="table table-sm table-borderless small mb-0">
                            <tr><td class="fw-semibold" width="40%">Bank</td><td>{{ $settings['payment_bank_name'] ?: '—' }}</td></tr>
                            <tr><td class="fw-semibold">No. Rek.</td><td>{{ $settings['payment_bank_account_number'] ?: '—' }}</td></tr>
                            <tr><td class="fw-semibold">Atas Nama</td><td>{{ $settings['payment_bank_account_name'] ?: '—' }}</td></tr>
                        </table>
                        @if($settings['payment_instructions'])
                            <p class="small text-muted mt-2 mb-0">{{ $settings['payment_instructions'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
