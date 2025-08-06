@extends('admin.layouts.app')

@section('title', 'Debug Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bug me-2"></i>
                        Debug Menu & User Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Current User Information:</h6>
                            @if(Auth::check())
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>User ID:</strong> {{ Auth::user()->id }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Name:</strong> {{ Auth::user()->name }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Email:</strong> {{ Auth::user()->email }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Is Admin:</strong> 
                                        <span class="badge bg-{{ Auth::user()->is_admin ? 'success' : 'secondary' }}">
                                            {{ Auth::user()->is_admin ? 'YES' : 'NO' }}
                                        </span>
                                    </li>
                                    @if(isset(Auth::user()->role))
                                    <li class="list-group-item">
                                        <strong>Role:</strong> {{ Auth::user()->role }}
                                    </li>
                                    @endif
                                </ul>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    User not authenticated
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary">Session Information:</h6>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Admin Account in Session:</strong>
                                    @if(session('admin_account'))
                                        <span class="badge bg-success">EXISTS</span>
                                        <br><small>{{ session('admin_account')->full_name ?? 'N/A' }}</small>
                                    @else
                                        <span class="badge bg-secondary">NONE</span>
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <strong>Laravel Version:</strong> {{ app()->version() }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Current Route:</strong> {{ request()->route()->getName() ?? 'N/A' }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Database Tables Status:</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Accounts Table</h6>
                                            @php
                                                $accountsTableExists = false;
                                                try {
                                                    $accountsTableExists = Schema::hasTable('accounts');
                                                } catch (\Exception $e) {
                                                    $accountsTableExists = false;
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $accountsTableExists ? 'success' : 'danger' }} fs-6">
                                                {{ $accountsTableExists ? 'EXISTS' : 'NOT FOUND' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Users Table</h6>
                                            @php
                                                $usersTableExists = Schema::hasTable('users');
                                            @endphp
                                            <span class="badge bg-{{ $usersTableExists ? 'success' : 'danger' }} fs-6">
                                                {{ $usersTableExists ? 'EXISTS' : 'NOT FOUND' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6>Publishers Table</h6>
                                            @php
                                                $publishersTableExists = Schema::hasTable('publishers');
                                            @endphp
                                            <span class="badge bg-{{ $publishersTableExists ? 'success' : 'danger' }} fs-6">
                                                {{ $publishersTableExists ? 'EXISTS' : 'NOT FOUND' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Available Menu Links:</h6>
                            <div class="list-group">
                                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                                <a href="{{ route('admin.accounts.index') }}" class="list-group-item list-group-item-action list-group-item-warning">
                                    <i class="fas fa-users-cog me-2"></i>Kelola Akun
                                    <span class="badge bg-warning text-dark ms-2">TARGET MENU</span>
                                </a>
                                <a href="{{ route('admin.journals.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-book me-2"></i>Kelola Jurnal
                                </a>
                                <a href="{{ route('admin.publishers.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-building me-2"></i>Kelola Penerbit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
