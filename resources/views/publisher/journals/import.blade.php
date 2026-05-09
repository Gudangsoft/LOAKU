@extends('publisher.layout')

@section('title', 'Import Jurnal')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-upload me-2"></i>Import Data Jurnal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Petunjuk Import</h6>
                        <ul class="mb-0">
                            <li>File harus berformat <strong>CSV (.csv)</strong></li>
                            <li>Ukuran file maksimal 2MB</li>
                            <li>Download template CSV untuk format yang benar</li>
                            <li>Jurnal yang sudah ada akan di-update berdasarkan nama jurnal</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('publisher.journals.template') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download Template CSV
                        </a>
                    </div>

                    <form action="{{ route('publisher.journals.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <i class="fas fa-file-csv me-1"></i>Pilih File CSV <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file" accept=".csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: .csv — Maksimal 2MB</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('publisher.journals.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>Import Jurnal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-table me-2"></i>Format Kolom CSV</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Keterangan</th>
                                    <th>Wajib</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><code>nama_jurnal</code></td><td>Nama jurnal</td><td><span class="badge bg-danger">Ya</span></td></tr>
                                <tr><td><code>p_issn</code></td><td>P-ISSN (cetak)</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>e_issn</code></td><td>E-ISSN (elektronik)</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>chief_editor</code></td><td>Nama editor kepala</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>email</code></td><td>Email kontak jurnal</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>website</code></td><td>URL website jurnal</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>deskripsi</code></td><td>Deskripsi singkat</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>sinta_id</code></td><td>ID SINTA</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>doi_prefix</code></td><td>Prefix DOI</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>garuda_id</code></td><td>ID Garuda</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                                <tr><td><code>accreditation_level</code></td><td>Level akreditasi (mis. Sinta 2)</td><td><span class="badge bg-secondary">Tidak</span></td></tr>
                            </tbody>
                        </table>
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
    if (!file) return;
    if (file.size / 1024 / 1024 > 2) {
        alert('File terlalu besar! Maksimal 2MB.');
        e.target.value = '';
    }
});
</script>
@endpush
