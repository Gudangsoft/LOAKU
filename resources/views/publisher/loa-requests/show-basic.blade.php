<!DOCTYPE html>
<html>
<head>
    <title>LOA Request Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>LOA Request Detail</h1>
        
        <p><strong>ID:</strong> {{ $loaRequest->id }}</p>
        <p><strong>No Reg:</strong> {{ $loaRequest->no_reg }}</p>
        <p><strong>Status:</strong> {{ $loaRequest->status }}</p>
        <p><strong>Article ID:</strong> {{ $loaRequest->article_id }}</p>
        <p><strong>Title:</strong> {{ $loaRequest->title }}</p>
        <p><strong>Authors:</strong> {{ $loaRequest->authors }}</p>
        <p><strong>Volume:</strong> {{ $loaRequest->volume }}</p>
        <p><strong>Number:</strong> {{ $loaRequest->number }}</p>
        <p><strong>Month:</strong> {{ $loaRequest->month }}</p>
        <p><strong>Year:</strong> {{ $loaRequest->year }}</p>
        
        @if($loaRequest->journal)
            <p><strong>Journal:</strong> {{ $loaRequest->journal->name }}</p>
            
            @if($loaRequest->journal->publisher)
                <p><strong>Publisher:</strong> {{ $loaRequest->journal->publisher->name }}</p>
            @endif
        @endif
        
        <a href="/publisher/loa-requests" class="btn btn-primary">Back</a>
    </div>
</body>
</html>
