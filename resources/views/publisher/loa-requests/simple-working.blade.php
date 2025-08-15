@extends('publisher.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2>LOA Request Detail</h2>
            
            <div class="card">
                <div class="card-body">
                    <h5>No Reg: {{ $loaRequest->no_reg }}</h5>
                    <p>Status: {{ $loaRequest->status }}</p>
                    <p>Article ID: {{ $loaRequest->article_id }}</p>
                    
                    @if($loaRequest->journal)
                        <p>Journal: {{ $loaRequest->journal->name }}</p>
                        @if($loaRequest->journal->publisher)
                            <p>Publisher: {{ $loaRequest->journal->publisher->name }}</p>
                        @endif
                    @endif
                    
                    <a href="{{ route('publisher.loa-requests.index') }}" class="btn btn-primary">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
