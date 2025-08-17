@extends('layouts.admin')

@section('title', 'Journals')
@section('subtitle', 'Manage journal information')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-book me-2"></i>
                Data Jurnal
            </h1>
            <p class="mb-0 text-muted">Kelola data jurnal ilmiah dalam sistem</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Export/Import Buttons -->
            <div class="btn-group me-2" role="group">
                <a href="{{ route('admin.journals.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download me-1"></i>
                    Export Excel
                </a>
                <a href="{{ route('admin.journals.import.form') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-upload me-1"></i>
                    Import Excel
                </a>
                <a href="{{ route('admin.journals.template') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-excel me-1"></i>
                    Template
                </a>
            </div>
            <a href="{{ route('admin.journals.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Tambah Jurnal
            </a>
        </div>
    </div>

    <!-- Journals Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>
                Daftar Jurnal ({{ $journals->total() }} jurnal)
            </h6>
        </div>
        <div class="card-body">
            @if($journals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Nama Jurnal</th>
                                <th>ISSN</th>
                                <th>Editor-in-Chief</th>
                                <th>Penerbit</th>
                                <th>Website</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($journals as $index => $journal)
                                <tr>
                                    <td>{{ $journals->firstItem() + $index }}</td>
                                    <td>
                                        @if($journal->logo)
                                            <img src="{{ asset('storage/' . $journal->logo) }}" 
                                                 alt="Logo" 
                                                 class="img-thumbnail" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $journal->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($journal->e_issn)
                                            <div><small class="badge bg-info">E-ISSN: {{ $journal->e_issn }}</small></div>
                                        @endif
                                        @if($journal->p_issn)
                                            <div><small class="badge bg-secondary">P-ISSN: {{ $journal->p_issn }}</small></div>
                                        @endif
                                        @if(!$journal->e_issn && !$journal->p_issn)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $journal->chief_editor }}</td>
                                    <td>{{ $journal->publisher->name }}</td>
                                    <td>
                                        @if($journal->website)
                                            <a href="{{ $journal->website }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.journals.show', $journal) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.journals.edit', $journal) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.journals.destroy', $journal) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus jurnal ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $journals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada data jurnal</h5>
                    <p class="text-muted">Klik tombol "Tambah Jurnal" untuk menambahkan jurnal baru</p>
                    <a href="{{ route('admin.journals.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Jurnal Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
