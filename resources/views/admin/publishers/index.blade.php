@extends('layouts.admin')

@section('title', 'Publishers')
@section('subtitle', 'Manage publisher information')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-building me-2"></i>
                Data Penerbit
            </h1>
            <p class="mb-0 text-muted">Kelola data penerbit/institusi dalam sistem</p>
        </div>
        <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Tambah Penerbit
        </a>
    </div>

    <!-- Publishers Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>
                Daftar Penerbit ({{ $publishers->total() }} penerbit)
            </h6>
        </div>
        <div class="card-body">
            @if($publishers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Nama Penerbit</th>
                                <th>Alamat</th>
                                <th>Kontak</th>
                                <th>Jurnal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($publishers as $index => $publisher)
                                <tr>
                                    <td>{{ $publishers->firstItem() + $index }}</td>
                                    <td>
                                        @if($publisher->logo)
                                            <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                                 alt="Logo" 
                                                 class="img-thumbnail" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-building text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $publisher->name }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $publisher->email }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $publisher->address }}">
                                            {{ $publisher->address }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($publisher->phone)
                                            <div><i class="fas fa-phone me-1"></i>{{ $publisher->phone }}</div>
                                        @endif
                                        @if($publisher->whatsapp)
                                            <div><i class="fab fa-whatsapp me-1"></i>{{ $publisher->whatsapp }}</div>
                                        @endif
                                        @if($publisher->website)
                                            <div>
                                                <i class="fas fa-globe me-1"></i>
                                                <a href="{{ $publisher->website }}" target="_blank" class="text-decoration-none">
                                                    {{ $publisher->website }}
                                                </a>
                                            </div>
                                        @endif
                                        @if(!$publisher->phone && !$publisher->whatsapp && !$publisher->website)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $publisher->journals_count }} jurnal</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.publishers.show', $publisher) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.publishers.edit', $publisher) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($publisher->journals_count == 0)
                                                <form action="{{ route('admin.publishers.destroy', $publisher) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm" 
                                                            title="Hapus"
                                                            onclick="return confirm('Yakin ingin menghapus penerbit ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-danger btn-sm" 
                                                        title="Tidak dapat dihapus (memiliki jurnal)"
                                                        disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $publishers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-building fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada data penerbit</h5>
                    <p class="text-muted">Klik tombol "Tambah Penerbit" untuk menambahkan penerbit/institusi baru</p>
                    <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Penerbit Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
