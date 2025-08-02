<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function testPublisher()
    {
        try {
            // Check database connection
            DB::connection()->getPdo();
            $output[] = "✅ Database connection: OK";
            
            // Check table exists
            if (!Schema::hasTable('publishers')) {
                $output[] = "❌ Publishers table: NOT EXISTS";
                return response()->json(['status' => 'error', 'checks' => $output]);
            }
            $output[] = "✅ Publishers table: EXISTS";
            
            // Check columns
            $columns = Schema::getColumnListing('publishers');
            $output[] = "📋 Table columns: " . implode(', ', $columns);
            
            if (!in_array('website', $columns)) {
                $output[] = "❌ Website column: MISSING";
            } else {
                $output[] = "✅ Website column: EXISTS";
            }
            
            // Test publisher creation
            $testData = [
                'name' => 'API Test Publisher',
                'address' => 'API Test Address 123',
                'email' => 'apitest@example.com',
                'website' => 'https://apitest.example.com'
            ];
            
            $publisher = Publisher::create($testData);
            $output[] = "✅ Publisher created successfully with ID: " . $publisher->id;
            
            // Verify the data
            $saved = Publisher::find($publisher->id);
            $output[] = "📝 Saved data verification:";
            $output[] = "   - Name: " . $saved->name;
            $output[] = "   - Email: " . $saved->email;
            $output[] = "   - Website: " . ($saved->website ?: 'NULL');
            
            // Clean up
            $publisher->delete();
            $output[] = "🧹 Test data cleaned up";
            
            return response()->json([
                'status' => 'success',
                'message' => 'All tests passed!',
                'checks' => $output
            ]);
            
        } catch (\Exception $e) {
            $output[] = "❌ ERROR: " . $e->getMessage();
            $output[] = "📍 File: " . $e->getFile() . ":" . $e->getLine();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'checks' => $output
            ]);
        }
    }
}
