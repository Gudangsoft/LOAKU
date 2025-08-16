@extends('layouts.admin')

@section('title', 'System Logs')
@section('subtitle', 'Monitor application logs and system errors')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Action Buttons -->
        <div class="d-flex justify-content-end mb-4">
            @if(!empty($currentLog))
                <a href="{{ route('admin.system-logs.download', $currentLog) }}" class="btn btn-success me-2">
                    <i class="fas fa-download me-2"></i>Download
                </a>
                <form action="{{ route('admin.system-logs.clear', $currentLog) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus semua log?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Clear Log
                    </button>
                </form>
            @endif
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

    <div class="row">
        <!-- Log Files Sidebar -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Log Files</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($logFiles as $file)
                            <a href="{{ route('admin.system-logs.index', ['file' => $file['name']]) }}" 
                               class="list-group-item list-group-item-action {{ $currentLog === $file['name'] ? 'active' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $file['name'] }}</h6>
                                    <small>{{ $file['size'] }}</small>
                                </div>
                                <small>Modified: {{ $file['modified'] }}</small>
                            </a>
                        @empty
                            <div class="list-group-item">
                                <p class="mb-0 text-muted">Tidak ada log files</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Content -->
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Log Content: {{ $currentLog }}
                        <span class="badge bg-secondary ms-2">{{ count($logs) }} entries</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if(!empty($logs))
                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-sm table-bordered">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th width="150">Timestamp</th>
                                        <th width="80">Level</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr class="log-level-{{ strtolower($log['level']) }}">
                                            <td>
                                                <small>{{ $log['timestamp'] }}</small>
                                            </td>
                                            <td>
                                                @switch(strtolower($log['level']))
                                                    @case('emergency')
                                                    @case('alert')
                                                    @case('critical')
                                                    @case('error')
                                                        <span class="badge bg-danger">{{ $log['level'] }}</span>
                                                        @break
                                                    @case('warning')
                                                        <span class="badge bg-warning">{{ $log['level'] }}</span>
                                                        @break
                                                    @case('notice')
                                                    @case('info')
                                                        <span class="badge bg-info">{{ $log['level'] }}</span>
                                                        @break
                                                    @case('debug')
                                                        <span class="badge bg-secondary">{{ $log['level'] }}</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-primary">{{ $log['level'] }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <code class="text-wrap" style="font-size: 0.875rem;">{{ $log['message'] }}</code>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Log file kosong atau tidak ada log entries</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.log-level-error, .log-level-critical, .log-level-emergency, .log-level-alert {
    background-color: #fee;
}
.log-level-warning {
    background-color: #fff3cd;
}
.log-level-info, .log-level-notice {
    background-color: #e7f3ff;
}
.log-level-debug {
    background-color: #f8f9fa;
}

.list-group-item.active {
    background-color: #007bff;
    border-color: #007bff;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>

    </div>
</div>
@endsection

@push('styles')
<style>
.log-level-error, .log-level-critical, .log-level-emergency, .log-level-alert {
    background-color: #fee;
}
.log-level-warning {
    background-color: #fff3cd;
}
.log-level-info, .log-level-notice {
    background-color: #e7f3ff;
}
.log-level-debug {
    background-color: #f8f9fa;
}

.list-group-item.active {
    background-color: #007bff;
    border-color: #007bff;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>
@endpush
