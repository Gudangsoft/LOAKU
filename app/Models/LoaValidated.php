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
        return 'LOA' . date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}
