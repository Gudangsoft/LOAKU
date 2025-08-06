<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\Publisher;

class JournalTestController extends Controller
{
    public function testJournalData()
    {
        try {
            // Test apakah bisa mengambil data journal
            $journals = Journal::with('publisher')->get();
            $journalCount = $journals->count();
            $publishers = Publisher::all();
            $publisherCount = $publishers->count();
            
            $html = "<!DOCTYPE html><html><head><title>Test Journal Data</title></head><body>";
            $html .= "<h1>Test Journal Data</h1>";
            $html .= "<p><strong>Total Journals:</strong> {$journalCount}</p>";
            $html .= "<p><strong>Total Publishers:</strong> {$publisherCount}</p>";
            
            if ($journalCount > 0) {
                $html .= "<h2>Journal List:</h2><ul>";
                foreach ($journals as $journal) {
                    $html .= "<li>";
                    $html .= "<strong>{$journal->name}</strong><br>";
                    $html .= "Publisher: " . ($journal->publisher ? $journal->publisher->name : 'No Publisher') . "<br>";
                    $html .= "ISSN: {$journal->issn}<br>";
                    $html .= "</li><br>";
                }
                $html .= "</ul>";
            } else {
                $html .= "<p style='color: orange;'>❌ Tidak ada data journal - Ini penyebab combo box kosong!</p>";
                
                if ($publisherCount > 0) {
                    $html .= "<h2>Available Publishers:</h2><ul>";
                    foreach ($publishers as $publisher) {
                        $html .= "<li>{$publisher->name}</li>";
                    }
                    $html .= "</ul>";
                    $html .= "<p style='color: blue;'>ℹ️ Ada publisher tersedia. Perlu menambahkan data journal.</p>";
                } else {
                    $html .= "<p style='color: red;'>❌ Tidak ada publisher. Perlu menambahkan publisher dulu.</p>";
                }
            }
            
            $html .= "<h2>Test Journal Creation:</h2>";
            if ($publisherCount > 0) {
                try {
                    $firstPublisher = $publishers->first();
                    $testJournal = Journal::create([
                        'name' => 'Test Journal ' . date('Y-m-d H:i:s'),
                        'issn' => '1234-' . rand(1000, 9999),
                        'publisher_id' => $firstPublisher->id
                    ]);
                    
                    $html .= "<p style='color: green;'>✅ SUCCESS: Test journal created with ID: {$testJournal->id}</p>";
                    
                    // Clean up
                    $testJournal->delete();
                    $html .= "<p style='color: blue;'>🧹 Test journal deleted</p>";
                    
                } catch (\Exception $e) {
                    $html .= "<p style='color: red;'>❌ ERROR creating journal: " . $e->getMessage() . "</p>";
                }
            } else {
                $html .= "<p style='color: red;'>❌ Cannot test journal creation - no publishers available</p>";
            }
            
            $html .= "</body></html>";
            
            return response($html);
            
        } catch (\Exception $e) {
            return response("<h1>Error</h1><p style='color: red;'>" . $e->getMessage() . "</p>");
        }
    }
}
