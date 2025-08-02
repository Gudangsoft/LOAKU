@extends('layouts.app')

@section('title', 'Edit Penerbit - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Penerbit
                    </h4>
                    <p class="mb-0 mt-2">Perbarui informasi penerbit/institusi: <strong>{{ $publisher->name }}</strong></p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.publishers.update', $publisher) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Penerbit -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-building me-1"></i>
                                Nama Penerbit/Institusi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $publisher->name) }}" 
                                   placeholder="Contoh: Universitas Teknologi Indonesia"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Masukkan alamat lengkap penerbit/institusi"
                                      required>{{ old('address', $publisher->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Nomor Telepon
                                </label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $publisher->phone) }}" 
                                       placeholder="+62-21-1234567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- WhatsApp -->
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label">
                                    <i class="fab fa-whatsapp me-1"></i>
                                    WhatsApp
                                </label>
                                <input type="text" 
                                       class="form-control @error('whatsapp') is-invalid @enderror" 
                                       id="whatsapp" 
                                       name="whatsapp" 
                                       value="{{ old('whatsapp', $publisher->whatsapp) }}" 
                                       placeholder="+62-812-3456-7890">
                                @error('whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $publisher->email) }}" 
                                   placeholder="publisher@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Logo -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="fas fa-image me-1"></i>
                                Logo Penerbit
                            </label>
                            
                            @if($publisher->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                         alt="Current Logo" 
                                         class="img-thumbnail" 
                                         style="max-height: 100px;">
                                    <p class="text-muted small mt-1">Logo saat ini</p>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" 
                                   name="logo" 
                                   accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah logo.</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="fas fa-save me-1"></i>
                                Update Penerbit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-info-circle me-1"></i>
                        Informasi Update
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Nama penerbit, alamat, dan email wajib diisi</li>
                        <li><i class="fas fa-check text-success me-2"></i>Nomor telepon dan WhatsApp bersifat opsional</li>
                        <li><i class="fas fa-check text-success me-2"></i>Logo baru akan mengganti logo lama jika diupload</li>
                        <li><i class="fas fa-check text-success me-2"></i>Email harus unik dan tidak boleh sama dengan penerbit lain</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
