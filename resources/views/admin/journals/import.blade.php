@extends('layouts.admin')

@section('title', 'Import Jurnal')
@section('subtitle', 'Import data jurnal dari file Excel')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-upload me-2"></i>
                        Import Data Jurnal
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Instructions -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>
                            Petunjuk Import
                        </h6>
                        <ul class="mb-0">
                            <li>File harus berformat Excel (.xlsx, .xls) atau CSV</li>
                            <li>Ukuran file maksimal 2MB</li>
                            <li>Download template untuk format yang benar</li>
                            <li>Jurnal yang sudah ada akan di-update berdasarkan nama jurnal</li>
                        </ul>
                    </div>

                    <!-- Download Template -->
                    <div class="mb-4">
                        <a href="{{ route('admin.journals.template') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>
                            Download Template Excel
                        </a>
                    </div>

                    <!-- Import Form -->
                    <form action="{{ route('admin.journals.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <i class="fas fa-file-excel me-1"></i>
                                Pilih File Excel <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file" 
                                   accept=".xlsx,.xls,.csv" 
                                   required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Format yang didukung: .xlsx, .xls, .csv (Maksimal 2MB)
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.journals.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>
                                Import Jurnal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Format Guide -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Format File Excel
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>nama_jurnal</th>
                                    <th>deskripsi</th>
                                    <th>issn</th>
                                    <th>e_issn</th>
                                    <th>website</th>
                                    <th>email</th>
                                    <th>alamat</th>
                                    <th>publisher_email</th>
                                    <th>status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jurnal Teknologi Informasi</td>
                                    <td>Jurnal yang membahas...</td>
                                    <td>1234-5678</td>
                                    <td>8765-4321</td>
                                    <td>https://jti.example.com</td>
                                    <td>editor@jti.com</td>
                                    <td>Jl. Teknologi No. 123</td>
                                    <td>publisher@example.com</td>
                                    <td>active</td>
                                </tr>
                                <tr>
                                    <td>Jurnal Ilmu Komputer</td>
                                    <td>Jurnal penelitian...</td>
                                    <td>2345-6789</td>
                                    <td>9876-5432</td>
                                    <td>https://jik.example.com</td>
                                    <td>editor@jik.com</td>
                                    <td>Jl. Komputer No. 456</td>
                                    <td>publisher@example.com</td>
                                    <td>active</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Keterangan:</h6>
                        <ul class="small">
                            <li><strong>nama_jurnal:</strong> Wajib diisi, nama jurnal yang unik</li>
                            <li><strong>deskripsi:</strong> Deskripsi singkat jurnal (opsional)</li>
                            <li><strong>issn/e_issn:</strong> Nomor ISSN (opsional)</li>
                            <li><strong>website:</strong> URL website jurnal (opsional)</li>
                            <li><strong>email:</strong> Email kontak jurnal (opsional)</li>
                            <li><strong>alamat:</strong> Alamat penerbit (opsional)</li>
                            <li><strong>publisher_email:</strong> Email publisher (untuk menentukan pemilik, opsional)</li>
                            <li><strong>status:</strong> active/inactive/pending (default: active)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 2) {
            alert('File terlalu besar! Maksimal 2MB.');
            e.target.value = '';
            return;
        }
        
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                            'application/vnd.ms-excel', 
                            'text/csv'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan .xlsx, .xls, atau .csv');
            e.target.value = '';
            return;
        }
    }
});
</script>
@endpush
