@extends('publisher.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-building me-2"></i>Publisher Details</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('publisher.publishers.index') }}">Publishers</a></li>
                <li class="breadcrumb-item active">{{ $publisher->name }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('publisher.publishers.edit', $publisher) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('publisher.publishers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Publisher Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Name:</td>
                                <td>{{ $publisher->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>
                                    <a href="mailto:{{ $publisher->email }}">{{ $publisher->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Phone:</td>
                                <td>
                                    <a href="tel:{{ $publisher->phone }}">{{ $publisher->phone }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Website:</td>
                                <td>
                                    @if($publisher->website)
                                        <a href="{{ $publisher->website }}" target="_blank">
                                            {{ $publisher->website }} <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Address:</td>
                                <td>{{ $publisher->address }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Created:</td>
                                <td>{{ $publisher->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Last Updated:</td>
                                <td>{{ $publisher->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 text-center">
                        @if($publisher->logo)
                            <img src="{{ asset('storage/' . $publisher->logo) }}" 
                                 class="img-fluid rounded shadow" style="max-height: 200px;">
                        @else
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 120px; height: 120px; font-size: 48px;">
                                {{ strtoupper(substr($publisher->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="mb-3">
                            <h3 class="text-primary mb-0">{{ $publisher->journals()->count() }}</h3>
                            <small class="text-muted">Journals</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <h3 class="text-success mb-0">{{ $publisher->journals()->sum('id') ? \App\Models\LoaRequest::whereIn('journal_id', $publisher->journals()->pluck('id'))->count() : 0 }}</h3>
                            <small class="text-muted">LOA Requests</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($publisher->journals()->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Associated Journals</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($publisher->journals()->latest()->take(5)->get() as $journal)
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-center">
                            @if($journal->logo)
                                <img src="{{ asset('storage/' . $journal->logo) }}" 
                                     class="me-2 rounded" width="24" height="24">
                            @else
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center me-2" 
                                     style="width: 24px; height: 24px; font-size: 10px;">
                                    {{ strtoupper(substr($journal->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $journal->name }}</div>
                                @if($journal->e_issn || $journal->p_issn)
                                    <small class="text-muted">
                                        @if($journal->e_issn) e-ISSN: {{ $journal->e_issn }} @endif
                                        @if($journal->p_issn) p-ISSN: {{ $journal->p_issn }} @endif
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($publisher->journals()->count() > 5)
                    <div class="text-center mt-2">
                        <small class="text-muted">And {{ $publisher->journals()->count() - 5 }} more journals...</small>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
