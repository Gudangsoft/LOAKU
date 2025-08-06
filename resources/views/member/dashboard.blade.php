@extends('layouts.member')

@section('title', 'Dashboard Member')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <h2 class="mb-1">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard Publisher
                    </h2>
                    <p class="mb-0 opacity-75">Selamat datang, {{ Auth::user()->name }}! Kelola publisher, jurnal, dan LOA requests dengan mudah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ Auth::user()->publishers()->count() }}</h4>
                            <small>Publisher Saya</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-building fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ Auth::user()->journals()->count() }}</h4>
                            <small>Jurnal Saya</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-dark border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $my_requests['pending'] ?? 0 }}</h4>
                            <small>LOA Pending Review</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $my_requests['total'] ?? 0 }}</h4>
                            <small>Total LOA Requests</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('loa.create') }}" class="btn btn-primary w-100 p-3">
                                <i class="fas fa-plus-circle d-block mb-2 fa-2x"></i>
                                <strong>Request LOA Baru</strong>
                                <small class="d-block">Buat request LOA untuk artikel baru</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('member.requests') }}" class="btn btn-outline-primary w-100 p-3">
                                <i class="fas fa-list-alt d-block mb-2 fa-2x"></i>
                                <strong>Request Saya</strong>
                                <small class="d-block">Lihat semua LOA request Anda</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('loa.validated') }}" class="btn btn-outline-success w-100 p-3">
                                <i class="fas fa-search d-block mb-2 fa-2x"></i>
                                <strong>Cari LOA</strong>
                                <small class="d-block">Cari dan download LOA</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <a href="{{ route('loa.verify') }}" class="btn btn-outline-info w-100 p-3">
                                <i class="fas fa-check-circle d-block mb-2 fa-2x"></i>
                                <strong>Verifikasi LOA</strong>
                                <small class="d-block">Verifikasi keaslian LOA</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Profile -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Request Terbaru Saya
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recent_requests) && count($recent_requests) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Artikel</th>
                                        <th>Jurnal</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_requests as $request)
                                    <tr>
                                        <td>{{ $request->article_title }}</td>
                                        <td>{{ $request->journal->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada LOA request. <a href="{{ route('loa.create') }}" class="alert-link">Buat request pertama Anda</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Profil Saya
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                    </div>
                    <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Role:</strong> 
                        <span class="badge bg-info">Member</span>
                    </p>
                    <p><strong>Bergabung:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('member.profile') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Butuh Bantuan?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">Panduan singkat:</p>
                    <ul class="small">
                        <li>Request LOA untuk artikel yang sudah diterima</li>
                        <li>Upload dokumen pendukung lengkap</li>
                        <li>Tunggu review dari admin (1-3 hari kerja)</li>
                        <li>Download LOA setelah disetujui</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
