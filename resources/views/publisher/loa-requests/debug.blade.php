<!DOCTYPE html>
<html>
<head>
    <title>LOA Request Test</title>
</head>
<body>
    <h1>LOA Request Detail Test</h1>
    
    @if(isset($loaRequest))
        <p><strong>LOA Request ID:</strong> {{ $loaRequest->id }}</p>
        <p><strong>Status:</strong> {{ $loaRequest->status ?? 'No status' }}</p>
        <p><strong>Title:</strong> {{ $loaRequest->article_title ?? $loaRequest->title ?? 'No title' }}</p>
        <p><strong>Authors:</strong> {{ $loaRequest->authors ?? $loaRequest->author ?? 'No authors' }}</p>
        
        @if($loaRequest->journal)
            <p><strong>Journal:</strong> {{ $loaRequest->journal->name }}</p>
        @else
            <p><strong>Journal:</strong> No journal data</p>
        @endif
        
        <hr>
        <h3>Raw Data:</h3>
        <pre>{{ json_encode($loaRequest->toArray(), JSON_PRETTY_PRINT) }}</pre>
    @else
        <p>No LOA Request data found!</p>
    @endif
</body>
</html>
