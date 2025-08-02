@extends('layouts.app')

@section('title', 'Detail Penerbit - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Publisher Detail Card -->
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Detail Penerbit
                    </h4>
                    <p class="mb-0 mt-2">Informasi lengkap penerbit/institusi</p>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Logo Section -->
                        @if($publisher->logo)
                            <div class="col-md-3 text-center mb-4">
                                <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                     alt="{{ $publisher->name }} Logo" 
                                     class="img-fluid rounded shadow"
                                     style="max-height: 150px;">
                            </div>
                            <div class="col-md-9">
                        @else
                            <div class="col-md-12">
                        @endif
                        
                            <!-- Publisher Information -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-building me-1"></i>
                                    Nama Penerbit/Institusi
                                </label>
                                <p class="form-control-plaintext bg-light p-2 rounded">{{ $publisher->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Alamat
                                </label>
                                <p class="form-control-plaintext bg-light p-2 rounded">{{ $publisher->address }}</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-phone me-1"></i>
                                        Telepon
                                    </label>
                                    <p class="form-control-plaintext bg-light p-2 rounded">
                                        {{ $publisher->phone ?: '-' }}
                                    </p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fab fa-whatsapp me-1"></i>
                                        WhatsApp
                                    </label>
                                    <p class="form-control-plaintext bg-light p-2 rounded">
                                        {{ $publisher->whatsapp ?: '-' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </label>
                                <p class="form-control-plaintext bg-light p-2 rounded">{{ $publisher->email }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-globe me-1"></i>
                                    Website
                                </label>
                                <p class="form-control-plaintext bg-light p-2 rounded">
                                    @if($publisher->website)
                                        <a href="{{ $publisher->website }}" target="_blank" class="text-decoration-none">
                                            {{ $publisher->website }}
                                            <i class="fas fa-external-link-alt ms-1 small"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-warning text-dark me-md-2">
                            <i class="fas fa-edit me-1"></i>
                            Edit Penerbit
                        </a>
                        <form action="{{ route('admin.publishers.destroy', $publisher) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus penerbit ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Journals List -->
            @if($publisher->journals->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-1"></i>
                            Jurnal yang Dikelola
                            <span class="badge bg-primary ms-2">{{ $publisher->journals->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jurnal</th>
                                        <th>ISSN</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publisher->journals as $index => $journal)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $journal->name }}</td>
                                            <td>
                                                @if($journal->issn_print)
                                                    <small class="text-muted">Print:</small> {{ $journal->issn_print }}<br>
                                                @endif
                                                @if($journal->issn_online)
                                                    <small class="text-muted">Online:</small> {{ $journal->issn_online }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.journals.show', $journal) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Jurnal</h5>
                        <p class="text-muted">Penerbit ini belum memiliki jurnal yang terdaftar.</p>
                        <a href="{{ route('admin.journals.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Jurnal
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Publisher Stats -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="fas fa-chart-bar me-1"></i>
                        Statistik Penerbit
                    </h6>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="fas fa-book fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $publisher->journals->count() }}</h4>
                                <small class="text-muted">Total Jurnal</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $publisher->created_at->format('Y') }}</h4>
                                <small class="text-muted">Tahun Bergabung</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h4 class="mb-0">{{ $publisher->updated_at->diffForHumans() }}</h4>
                                <small class="text-muted">Terakhir Update</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
