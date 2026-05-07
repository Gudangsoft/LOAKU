@extends('layouts.admin')

@section('title', 'Audit Log')

@section('content')
<div style="padding:24px">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div>
            <h4 style="font-weight:800;color:#1E1B4B;margin:0">Audit Log</h4>
            <p style="color:#64748B;margin:4px 0 0;font-size:.875rem">Riwayat seluruh aksi admin di sistem</p>
        </div>
        <div style="background:#EEF2FF;border-radius:10px;padding:8px 16px;font-size:.82rem;color:#4F46E5;font-weight:600">
            <i class="fas fa-shield-alt me-1"></i>Total: {{ number_format($logs->total()) }} entri
        </div>
    </div>

    {{-- Filter --}}
    <div style="background:white;border-radius:14px;border:1px solid #E2E8F0;padding:16px 20px;margin-bottom:20px">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:4px">Cari</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm"
                       placeholder="Cari deskripsi atau nama user...">
            </div>
            <div class="col-md-3">
                <label style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:4px">Jenis Aksi</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                    <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                        {{ str_replace('_', ' ', ucfirst($act)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div style="background:white;border-radius:14px;border:1px solid #E2E8F0;overflow:hidden">
        <table class="table table-hover mb-0" style="font-size:.875rem">
            <thead style="background:#F8FAFC;border-bottom:2px solid #E2E8F0">
                <tr>
                    <th style="padding:12px 16px;font-weight:700;color:#374151;width:160px">Waktu</th>
                    <th style="padding:12px 16px;font-weight:700;color:#374151;width:140px">Admin</th>
                    <th style="padding:12px 16px;font-weight:700;color:#374151;width:140px">Aksi</th>
                    <th style="padding:12px 16px;font-weight:700;color:#374151">Deskripsi</th>
                    <th style="padding:12px 16px;font-weight:700;color:#374151;width:100px">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="padding:10px 16px;color:#64748B;white-space:nowrap">
                        <div style="font-weight:600;color:#374151">{{ $log->created_at->format('d/m/Y') }}</div>
                        <div style="font-size:.78rem">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td style="padding:10px 16px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:28px;height:28px;background:linear-gradient(135deg,#6366F1,#06B6D4);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:.7rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                            </div>
                            <span style="font-weight:600;color:#374151">{{ $log->user_name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 16px">
                        @php
                            $badgeColor = match($log->action) {
                                'approve_loa'        => ['bg'=>'#DCFCE7','color'=>'#166534'],
                                'reject_loa'         => ['bg'=>'#FEE2E2','color'=>'#991B1B'],
                                'activate_publisher' => ['bg'=>'#DBEAFE','color'=>'#1E40AF'],
                                'suspend_publisher'  => ['bg'=>'#FEF3C7','color'=>'#92400E'],
                                'delete_user'        => ['bg'=>'#FFE4E6','color'=>'#9F1239'],
                                'create_user'        => ['bg'=>'#F0FDF4','color'=>'#14532D'],
                                'change_role'        => ['bg'=>'#EDE9FE','color'=>'#4C1D95'],
                                default              => ['bg'=>'#F1F5F9','color'=>'#475569'],
                            };
                        @endphp
                        <span style="background:{{ $badgeColor['bg'] }};color:{{ $badgeColor['color'] }};padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;white-space:nowrap">
                            {{ str_replace('_', ' ', ucfirst($log->action)) }}
                        </span>
                    </td>
                    <td style="padding:10px 16px;color:#374151">
                        {{ $log->description }}
                        @if($log->properties)
                            <button class="btn btn-link btn-sm p-0 ms-1" style="font-size:.75rem;color:#6366F1"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#props-{{ $log->id }}">
                                detail
                            </button>
                            <div class="collapse" id="props-{{ $log->id }}">
                                <div style="margin-top:6px;background:#F8FAFC;border-radius:6px;padding:8px 12px;font-size:.78rem;font-family:monospace;color:#64748B">
                                    @foreach($log->properties as $k => $v)
                                        <div><strong>{{ $k }}:</strong> {{ $v }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>
                    <td style="padding:10px 16px;color:#94A3B8;font-size:.78rem;font-family:monospace">
                        {{ $log->ip_address }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:40px;text-align:center;color:#94A3B8">
                        <i class="fas fa-clipboard-list fa-2x mb-2 d-block"></i>
                        Belum ada log aktivitas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($logs->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #E2E8F0">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
