<!DOCTYPE html>
<html>
<head>
    <title>Test Simple</title>
</head>
<body>
    <h1>Test LOA Request</h1>
    <p>ID: {{ $loaRequest->id ?? 'No ID' }}</p>
    <p>Title: {{ $loaRequest->article_title ?? 'No Title' }}</p>
    <p>Status: {{ $loaRequest->status ?? 'No Status' }}</p>
    <p>Author: {{ $loaRequest->author ?? 'No Author' }}</p>
    
    @if(isset($loaRequest->journal))
        <p>Journal: {{ $loaRequest->journal->name ?? 'No Journal Name' }}</p>
    @else
        <p>Journal: Not loaded</p>
    @endif
</body>
</html>
