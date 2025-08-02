<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaValidated extends Model
{
    use HasFactory;

    protected $table = 'loa_validated';

    protected $fillable = [
        'loa_request_id',
        'loa_code',
        'pdf_path_id',
        'pdf_path_en',
        'verification_url',
    ];

    public function loaRequest()
    {
        return $this->belongsTo(LoaRequest::class);
    }

    public static function generateLoaCode()
    {
        // Format: LOA + YYYYMMDD + sequential number (6 digits)
        $today = date('Ymd');
        $prefix = 'LOA' . $today;
        
        // Get the last LOA code for today
        $lastLoa = self::where('loa_code', 'LIKE', $prefix . '%')
            ->orderBy('loa_code', 'desc')
            ->first();
        
        if ($lastLoa) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastLoa->loa_code, -6);
            $newSequence = $lastSequence + 1;
        } else {
            // First LOA for today
            $newSequence = 1;
        }
        
        return $prefix . str_pad($newSequence, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Generate LOA code based on article ID format
     * Format: LOA + YYYYMMDD + ArticleID + Sequential
     */
    public static function generateLoaCodeWithArticleId($articleId = null)
    {
        $today = date('Ymd');
        $articlePart = $articleId ? str_replace(['ART', 'art'], '', $articleId) : '001';
        $baseCode = 'LOA' . $today . $articlePart;
        
        // Check if this combination exists
        $existingCount = self::where('loa_code', 'LIKE', $baseCode . '%')->count();
        $sequential = str_pad($existingCount + 1, 2, '0', STR_PAD_LEFT);
        
        return $baseCode . $sequential;
    }
}
