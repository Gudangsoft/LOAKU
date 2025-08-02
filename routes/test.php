<?php

use Illuminate\Support\Facades\Route;
use App\Models\Publisher;

Route::get('/test-publisher', function () {
    try {
        // Test database connection and table structure
        $publishers = Publisher::all();
        $columns = Schema::getColumnListing('publishers');
        
        return response()->json([
            'status' => 'success',
            'publishers_count' => $publishers->count(),
            'table_columns' => $columns,
            'test_data' => $publishers->take(3)
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
