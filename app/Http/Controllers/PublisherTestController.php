<?php

namespace App\Http\Controllers;

use Illuminate\Http\Controller;
use App\Models\Publisher;

class PublisherTestController extends Controller
{
    public function testPublisherData()
    {
        try {
            // Test apakah bisa mengambil data publisher
            $publishers = Publisher::all();
            $publisherCount = $publishers->count();
            
            $html = "<!DOCTYPE html><html><head><title>Test Publisher Data</title></head><body>";
            $html .= "<h1>Test Publisher Data</h1>";
            $html .= "<p><strong>Total Publishers:</strong> {$publisherCount}</p>";
            
            if ($publisherCount > 0) {
                $html .= "<h2>Publisher List:</h2><ul>";
                foreach ($publishers as $publisher) {
                    $html .= "<li>";
                    $html .= "<strong>{$publisher->name}</strong><br>";
                    $html .= "Email: {$publisher->email}<br>";
                    $html .= "Phone: {$publisher->phone}<br>";
                    if (isset($publisher->website)) {
                        $html .= "Website: {$publisher->website}<br>";
                    }
                    $html .= "</li><br>";
                }
                $html .= "</ul>";
            } else {
                $html .= "<p style='color: orange;'>Tidak ada data publisher</p>";
            }
            
            $html .= "<h2>Test Publisher Creation:</h2>";
            try {
                $testPublisher = Publisher::create([
                    'name' => 'Test Publisher ' . date('Y-m-d H:i:s'),
                    'address' => 'Test Address',
                    'phone' => '08123456789',
                    'whatsapp' => '08123456789',
                    'email' => 'test' . time() . '@example.com',
                    'website' => 'https://test-publisher.com'
                ]);
                
                $html .= "<p style='color: green;'>✅ SUCCESS: Test publisher created with ID: {$testPublisher->id}</p>";
                
                // Clean up
                $testPublisher->delete();
                $html .= "<p style='color: blue;'>🧹 Test publisher deleted</p>";
                
            } catch (\Exception $e) {
                $html .= "<p style='color: red;'>❌ ERROR: " . $e->getMessage() . "</p>";
            }
            
            $html .= "</body></html>";
            
            return response($html);
            
        } catch (\Exception $e) {
            return response("<h1>Error</h1><p style='color: red;'>" . $e->getMessage() . "</p>");
        }
    }
}
