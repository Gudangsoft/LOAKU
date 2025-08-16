@extends('admin.layouts.app')

@section('title', 'Test Menu Settings')

@section('content')
<div class="container-fluid">
    <h1>Test Menu Settings</h1>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Menu Settings Test</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{ route('admin.website-settings.index') }}" class="btn btn-primary">
                                <i class="fas fa-cogs"></i> Website Settings
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-success">
                                <i class="fas fa-users"></i> Kelola User
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.system-logs.index') }}" class="btn btn-info">
                                <i class="fas fa-list-alt"></i> System Logs
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
