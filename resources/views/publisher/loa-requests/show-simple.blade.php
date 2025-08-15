@extends('publisher.layout')

@section('content')
<div class="container py-4">
    <h3>Detail LOA Request</h3>
    
    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <td><strong>No Registrasi:</strong></td>
                    <td>{{ $loaRequest->no_reg }}</td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        <span class="badge bg-{{ $loaRequest->status === 'approved' ? 'success' : ($loaRequest->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($loaRequest->status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Article ID:</strong></td>
                    <td>{{ $loaRequest->article_id }}</td>
                </tr>
                <tr>
                    <td><strong>Journal:</strong></td>
                    <td>{{ $loaRequest->journal->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Volume:</strong></td>
                    <td>{{ $loaRequest->volume }}</td>
                </tr>
                <tr>
                    <td><strong>Number:</strong></td>
                    <td>{{ $loaRequest->number }}</td>
                </tr>
                <tr>
                    <td><strong>Month:</strong></td>
                    <td>{{ $loaRequest->month }}</td>
                </tr>
                <tr>
                    <td><strong>Year:</strong></td>
                    <td>{{ $loaRequest->year }}</td>
                </tr>
                <tr>
                    <td><strong>Title:</strong></td>
                    <td>{{ $loaRequest->title }}</td>
                </tr>
                <tr>
                    <td><strong>Authors:</strong></td>
                    <td>{{ $loaRequest->authors }}</td>
                </tr>
                <tr>
                    <td><strong>Corresponding Email:</strong></td>
                    <td>{{ $loaRequest->corresponding_email }}</td>
                </tr>
                <tr>
                    <td><strong>Created:</strong></td>
                    <td>{{ $loaRequest->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
            
            @if($loaRequest->status === 'approved')
                <div class="mt-3">
                    <a href="{{ route('publisher.loa-requests.download', $loaRequest->id) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Download LOA
                    </a>
                </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
