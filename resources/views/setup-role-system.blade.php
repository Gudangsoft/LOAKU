@extends('layouts.app')

@section('title', 'Setup Role System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Setup Role-Based Access Control System
                    </h4>
                </div>
                <div class="card-body">
                    <div id="setup-status"></div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Setup Role System:</strong> Klik tombol di bawah untuk membuat tabel accounts dan akun administrator default.
                    </div>

                    <div class="text-center">
                        <button id="setup-btn" class="btn btn-primary btn-lg">
                            <i class="fas fa-magic me-2"></i>
                            Setup Role System
                        </button>
                    </div>

                    <div class="mt-4" style="display: none;" id="success-info">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Setup Berhasil!</h5>
                            <p class="mb-0">Akun administrator telah dibuat:</p>
                            <ul class="mb-0 mt-2">
                                <li><strong>Email:</strong> admin@loaku.com</li>
                                <li><strong>Password:</strong> admin123</li>
                                <li><strong>Role:</strong> Administrator</li>
                            </ul>
                            <div class="mt-3">
                                <a href="/admin/login" class="btn btn-success">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    Login sebagai Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('setup-btn').addEventListener('click', function() {
    const btn = this;
    const statusDiv = document.getElementById('setup-status');
    const successDiv = document.getElementById('success-info');
    
    // Show loading
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Setting up...';
    btn.disabled = true;
    
    statusDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i>Creating accounts table and default admin...</div>';
    
    // Make AJAX request to setup
    fetch('/setup-role-system', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check me-2"></i>' + data.message + '</div>';
            successDiv.style.display = 'block';
            btn.style.display = 'none';
        } else {
            statusDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Error: ' + data.message + '</div>';
            btn.innerHTML = '<i class="fas fa-magic me-2"></i>Setup Role System';
            btn.disabled = false;
        }
    })
    .catch(error => {
        statusDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Error: ' + error.message + '</div>';
        btn.innerHTML = '<i class="fas fa-magic me-2"></i>Setup Role System';
        btn.disabled = false;
    });
});
</script>
@endsection
