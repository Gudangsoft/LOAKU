Route::get('/check-auth', function() {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['status' => 'Not authenticated']);
    }
    
    $journals = \App\Models\Journal::where('user_id', $user->id)->get();
    $loaRequest = \App\Models\LoaRequest::find(2);
    
    return response()->json([
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name
        ],
        'journals' => $journals->map(function($journal) {
            return [
                'id' => $journal->id,
                'name' => $journal->name,
                'user_id' => $journal->user_id
            ];
        }),
        'loa_request' => $loaRequest ? [
            'id' => $loaRequest->id,
            'journal_id' => $loaRequest->journal_id,
            'journal_name' => $loaRequest->journal->name ?? 'Unknown'
        ] : null,
        'journal_ids' => $journals->pluck('id'),
        'has_access' => $loaRequest && $journals->pluck('id')->contains($loaRequest->journal_id)
    ]);
})->middleware(['auth']);
