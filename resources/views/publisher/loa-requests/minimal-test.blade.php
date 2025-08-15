<!DOCTYPE html>
<html>
<head>
    <title>Minimal Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1>LOA Request Detail - Minimal Test</h1>
    <div class="card">
        <div class="card-body">
            <h5>No Reg: {{ $loaRequest->no_reg ?? 'Not found' }}</h5>
            <p>Status: {{ $loaRequest->status ?? 'No status' }}</p>
            <p>ID: {{ $loaRequest->id ?? 'No ID' }}</p>
            
            @if($loaRequest->journal ?? null)
                <p>Journal: {{ $loaRequest->journal->name ?? 'No journal name' }}</p>
            @else
                <p>No journal data</p>
            @endif
        </div>
    </div>
</body>
</html>
