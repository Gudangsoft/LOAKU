Route::get('/test-publisher-loa-templates', function() {
    try {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated']);
        }
        
        if ($user->role !== 'publisher') {
            return response()->json(['error' => 'User role is: ' . $user->role . ', not publisher']);
        }
        
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $templates = \App\Models\LoaTemplate::where(function($query) use ($publisher) {
            if ($publisher) {
                $query->where('publisher_id', $publisher->id)
                      ->orWhereNull('publisher_id');
            } else {
                $query->whereNull('publisher_id');
            }
        })->with('publisher')->get();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'publisher' => $publisher ? [
                'id' => $publisher->id,
                'name' => $publisher->name
            ] : null,
            'templates_count' => $templates->count(),
            'templates' => $templates->toArray()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->middleware(['auth']);
