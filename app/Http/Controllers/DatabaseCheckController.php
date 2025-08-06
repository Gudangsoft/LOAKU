<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseCheckController extends Controller
{
    public function checkPublishersTable()
    {
        try {
            // Check if table exists
            $tableExists = Schema::hasTable('publishers');
            
            // Get all columns
            $columns = DB::select("DESCRIBE publishers");
            
            // Check if website column exists
            $websiteExists = Schema::hasColumn('publishers', 'website');
            
            $html = "<!DOCTYPE html><html><head><title>Database Check</title></head><body>";
            $html .= "<h1>Publishers Table Check</h1>";
            $html .= "<p><strong>Table exists:</strong> " . ($tableExists ? 'YES' : 'NO') . "</p>";
            $html .= "<p><strong>Website column exists:</strong> " . ($websiteExists ? 'YES' : 'NO') . "</p>";
            
            $html .= "<h2>All Columns:</h2><ul>";
            foreach ($columns as $column) {
                $html .= "<li><strong>{$column->Field}</strong> - {$column->Type} - " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . "</li>";
            }
            $html .= "</ul>";
            
            // Try to create a test publisher
            if ($websiteExists) {
                $html .= "<h2>Test Publisher Creation:</h2>";
                try {
                    $testData = [
                        'name' => 'Test Publisher Check',
                        'address' => 'Test Address',
                        'phone' => '123456789',
                        'whatsapp' => '123456789',
                        'email' => 'test@check.com',
                        'website' => 'https://test.com'
                    ];
                    
                    $publisher = \App\Models\Publisher::create($testData);
                    $html .= "<p style='color: green;'>✅ SUCCESS: Test publisher created with ID: {$publisher->id}</p>";
                    
                    // Clean up
                    $publisher->delete();
                    $html .= "<p style='color: blue;'>🧹 Test publisher deleted</p>";
                    
                } catch (\Exception $e) {
                    $html .= "<p style='color: red;'>❌ ERROR: " . $e->getMessage() . "</p>";
                }
            }
            
            $html .= "</body></html>";
            
            return response($html);
            
        } catch (\Exception $e) {
            return response("<h1>Error</h1><p style='color: red;'>" . $e->getMessage() . "</p>");
        }
    }
}
