@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user me-2"></i>Publisher Profile</h2>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Update Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('publisher.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    
                    <h6 class="mb-3">Change Password (Optional)</h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Role</label>
                    <div class="text-capitalize">
                        <span class="badge bg-success">Publisher</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Member Since</label>
                    <div>{{ Auth::user()->created_at->format('F d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Email Verified</label>
                    <div>
                        @if(Auth::user()->email_verified_at)
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Verified
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Publishers Managed</label>
                    <div><strong>{{ Auth::user()->publishers()->count() }}</strong> publisher(s)</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Journals Managed</label>
                    <div><strong>{{ Auth::user()->journals()->count() }}</strong> journal(s)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
