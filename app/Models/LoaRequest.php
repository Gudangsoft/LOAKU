<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoaRequest extends Model
{
    use HasFactory;

    protected $table = 'loa_requests';

    protected $fillable = [
        'no_reg',
        'article_id',
        'volume',
        'number',
        'month',
        'year',
        'article_title',
        'author',
        'author_email',
        'journal_id',
        'status',
        'admin_notes',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function loaValidated()
    {
        return $this->hasOne(LoaValidated::class);
    }

    public function getEditionAttribute()
    {
        return "Volume : {$this->volume} Nomor: {$this->number} {$this->month} {$this->year}";
    }

    public static function generateNoReg($articleId)
    {
        $lastRequest = self::where('article_id', $articleId)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequential = $lastRequest ? 
            (int)substr($lastRequest->no_reg, -2) + 1 : 1;
        
        return 'LOASIP.' . $articleId . '.' . str_pad($sequential, 2, '0', STR_PAD_LEFT);
    }
}
