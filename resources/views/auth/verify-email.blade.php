@extends('layouts.app')

@section('title', 'Verifikasi Email - LOA SIPTENAN')

@section('content')
<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:40px 0">
    <div style="max-width:480px;width:100%;margin:0 auto;padding:0 16px">
        <div style="background:white;border-radius:20px;border:1px solid #E2E8F0;box-shadow:0 8px 40px rgba(0,0,0,.08);overflow:hidden">

            <div style="background:linear-gradient(135deg,#1E1B4B 0%,#312E81 60%,#1E40AF 100%);padding:32px;text-align:center">
                <div style="width:64px;height:64px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.75rem">
                    <i class="fas fa-envelope" style="color:white"></i>
                </div>
                <h4 style="color:white;font-weight:800;margin:0 0 6px">Verifikasi Email Anda</h4>
                <p style="color:#BAE6FD;font-size:.875rem;margin:0">Satu langkah lagi untuk mengaktifkan akun</p>
            </div>

            <div style="padding:32px">

                @if(session('success'))
                <div style="background:#DCFCE7;border:1px solid #A7F3D0;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:.875rem;color:#166534;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-check-circle"></i>{{ session('success') }}
                </div>
                @endif

                @if(session('resent'))
                <div style="background:#DBEAFE;border:1px solid #93C5FD;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:.875rem;color:#1D4ED8;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-paper-plane"></i>Link verifikasi baru telah dikirim ke email Anda.
                </div>
                @endif

                <p style="color:#374151;font-size:.9rem;line-height:1.7;margin-bottom:24px">
                    Kami telah mengirimkan link verifikasi ke <strong>{{ auth()->user()?->email }}</strong>.
                    Silakan buka email Anda dan klik link tersebut untuk mengaktifkan akun.
                </p>

                <div style="background:#F8FAFC;border-radius:12px;padding:16px;margin-bottom:24px;font-size:.83rem;color:#64748B">
                    <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
                        <i class="fas fa-info-circle" style="color:#6366F1;margin-top:2px"></i>
                        <span>Cek folder <strong>Spam/Junk</strong> jika email tidak ditemukan di inbox.</span>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px">
                        <i class="fas fa-clock" style="color:#F59E0B;margin-top:2px"></i>
                        <span>Link verifikasi berlaku selama <strong>24 jam</strong>.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:16px">
                    @csrf
                    <button type="submit"
                            style="width:100%;background:linear-gradient(135deg,#4F46E5,#06B6D4);color:white;border:none;border-radius:10px;padding:12px;font-weight:700;font-size:.9rem;cursor:pointer;transition:opacity .2s"
                            onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                        <i class="fas fa-redo me-2"></i>Kirim Ulang Email Verifikasi
                    </button>
                </form>

                @if(auth()->check())
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:100%;background:white;border:1.5px solid #E2E8F0;color:#64748B;border-radius:10px;padding:10px;font-weight:600;font-size:.875rem;cursor:pointer">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
