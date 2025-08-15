@extends('publisher.layout')

@section('content')
<div class="container py-4">
    <h1>Test LOA Request Detail</h1>
    
    <div class="card">
        <div class="card-header">
            <h5>LOA Request #{{ $loaRequest->id }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Title:</strong> {{ $loaRequest->article_title }}</p>
            <p><strong>Author:</strong> {{ $loaRequest->author }}</p>
            <p><strong>Status:</strong> {{ $loaRequest->status }}</p>
            <p><strong>Registration:</strong> {{ $loaRequest->no_reg }}</p>
        </div>
    </div>
</div>
@endsection
